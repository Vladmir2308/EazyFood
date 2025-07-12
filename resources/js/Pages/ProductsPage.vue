<script setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import {debounce} from "lodash";
import {reactive, ref, watch} from "vue";
import {onKeyStroke} from "@vueuse/core";

function setFormData(form, routeName){
    form.name = mainInputField.value
    form.post(route(routeName), {
        onSuccess: () => {
            selectedButtonId.value = null
            showProductFields.value = false
        }
    })
}

let props = defineProps({
    categoriesAndProducts: null,
    user_id: null,
})

const mainInputField = ref("")

const searchCategoryInputField = ref('')

const product = useForm({
    id: null,
    name: null,
    default_unit: null,
    category_id: null,
    category_name: null,
    user_id: props.user_id,
    type: 'Product'
})

const category = useForm({
    id: null,
    name: null,
    user_id: props.user_id,
    type: 'Category'
})


const buttons = ref({
    'Категории': {
        id: 1
    },
    'Продукты': {
        id: 2
    }
})


const selectedButtonId = ref()
const changeButtonId = (id) => {
    selectedButtonId.value = id

    if(id === 1)
        setFormData(category, 'product.position.store')
    if(id === 2)
        showProductFields.value = true
}

/* Form Methods */
const sendProductOrCategory = () => {
    if(selectedButtonId.value === 1)
        setFormData(category, 'product.position.store')

}

const deletePosition = (key, form, formKey, routeName) => {
    form[formKey] = key
    form.delete(route(routeName), {
        onSuccess: () => {
            showProductFields.value = false
        }
    })
}

/**/

/* Add Product */
const showProductFields = ref(false)

const searchCategoryRezult = ref()
const showCategoryList = ref(false)
const searchCategory = debounce(async (e) => {
    product.category_id = null

    if(e.target.value === '')
        return

    const { data } = await axios.get(route('product.category.search', e.target.value))

    searchCategoryRezult.value = data
}, 100)
const hideCategoryListOnBlur = () => {
    setTimeout(() => {
        showCategoryList.value = false
    }, 150)
}
const clickOnCategoryInList = (id, name) => {
    product.category_id = id
    searchCategoryInputField.value = name
}

const selectedUnits = ref()
const units = ref({
    'мл': {
        id: 1
    },
    'гр': {
        id: 2
    },
    'шт': {
        id: 3
    },
})
const selectUnitForProduct = (id, unitName) => {
    if(selectedUnits.value === id)
        selectedUnits.value = ''
    else
        selectedUnits.value = id

    product.default_unit = unitName
}

const addProduct = () => {
    product.category_name = searchCategoryInputField.value
    product.name = mainInputField.value

    product.post(route('product.position.store'), {
        onSuccess: () => {
            showProductFields.value = false
        }
    })
}
/* ... */

onKeyStroke(['1', '2', 'Enter'], (e) =>{
    if (e.altKey) {
        e.preventDefault()

        if (e.key === '1') {
            selectedButtonId.value = 1
            setFormData(category, 'product.position.store')
        }

        if (e.key === '2'){
            selectedButtonId.value = 2
            showProductFields.value = true
        }

        if(e.key === 'Enter')
            if(selectedButtonId.value === 2){
                addProduct()
            }

    }
})

watch(mainInputField, debounce((e) => {
    if(!e){
        showProductFields.value = false
        selectedButtonId.value = ''
    }

    router.get(route('product.index'), {
        mainInputField: e
    }, {
        preserveState: true,
    })
}, 200))

</script>

<template>
    <div class="w-2/4 bg-black m-auto height-calc mt-10 rounded-lg p-5 text-white">
        <div class="w-1/2 max-h-screen relative">
            <div class="flex gap-2 flex-col relative z-10">
                <label for="mainInput">Введи продукт/категорию</label>
                <form action="#" method="post" @keydown.enter.prevent>
                    <input v-model="mainInputField"
                           id="mainInput"
                           class="rounded-md text-black w-full" type="search">

                    <div v-if="category.errors.name" class="text-red-500">{{ category.errors.name }}</div>
                    <div v-if="product.errors.name" class="text-red-500">{{ category.errors.name }}</div>
                </form>
            </div>

            <div class="bg-gray-400 absolute top-[55px] z-0 left-0 w-full p-3 h-[550px] rounded-lg overflow-y-auto" v-auto-animate>
                <div class="mt-5"
                     v-if="categoriesAndProducts && typeof categoriesAndProducts === 'object' && !showProductFields"
                     v-for="(items, categoryItem) in categoriesAndProducts"
                     :key="categoryItem">

                    <div class="font-bold flex justify-between items-center bg-orange-400 p-2 rounded-md">
                        <div>{{ categoryItem }}</div>

                        <form v-if="items.user_id === $page.props.auth.user.id"
                              action="#" method="post"
                              @submit.prevent>
                            <div class="w-4 h-4 cursor-pointer"
                                 @click="deletePosition(categoryItem, category, 'name', 'product.position.delete')">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                            </div>
                        </form>
                    </div>

                    <div class="ml-5" v-auto-animate>
                        <div class="mt-2 flex justify-between items-center bg-green-500 p-2 rounded-md" v-for="item in items.products" :key="item.id">
                            <div class="font-bold"> {{ item.name }} </div>

                            <form action="#" method="post"
                                  @submit.prevent>
                                <div class="w-4 h-4 cursor-pointer"
                                     v-if="item.user_id === $page.props.auth.user.id"
                                     @click="deletePosition(item.id, product, 'id', 'product.position.delete')">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>
                                </div>
                            </form >
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-lg text-center font-bold" v-else-if="mainInputField.length === 0">Введите запрос</div>

                <div v-else-if="categoriesAndProducts === 'Ничего не найдено' || showProductFields">
                    <div v-if="!showProductFields">
                        <div class="text-center border-b-4 mt-4 text-lg font-bold">Ничего не найдено</div>

                        <div class="mt-2">
                            <h2 class="text-center text-lg font-bold">Куда добавляем бро?</h2>

                            <div class="mt-2 flex gap-2 justify-center">
                                <button v-for="(item, key) in buttons" :key="item.id"
                                        @click="changeButtonId(item.id)"
                                        :class="['p-2 rounded-lg  hover:bg-gray-500',
                                    item.id === selectedButtonId ? 'bg-gray-800' : 'bg-gray-300',
                                    ]">{{ key }}</button>
                            </div>
                        </div>
                    </div>

                    <div v-else
                         class="mt-4" >
                        <div class="relative">
                            <div>
                                <input
                                    type="text"
                                    v-model="searchCategoryInputField"
                                    @focus="showCategoryList = true"
                                    @blur="hideCategoryListOnBlur"
                                    @input="searchCategory"
                                    class="border p-2 w-full rounded text-black"
                                    placeholder="Введите категорию"
                                />
                            </div>

                            <div>
                                <ul v-if="showCategoryList && searchCategoryInputField.length > 0" v-auto-animate
                                    class="absolute z-10 bg-white text-black border mt-1 rounded shadow w-full max-h-48 overflow-auto"
                                >
                                    <li
                                        v-for="category in searchCategoryRezult"
                                        :key="category.id"
                                        @click="clickOnCategoryInList(category.id, category.name)"
                                        class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                    >
                                        {{ category.name }}
                                    </li>
                                </ul>
                            </div>

                            <div class="mt-2">
                                <h2 class="text-center">Единица измерения по умолчанию:</h2>

                                <div class="mt-2 flex gap-4 justify-center">
                                    <button v-for="(item, key) in units" :key="item.id"
                                            @click="selectUnitForProduct(item.id, key)"
                                            :class="['p-2 bg-purple-300 rounded-lg text-lg text-black hover:bg-purple-500 transition',
                                    item.id === selectedUnits ? 'bg-purple-800' : 'bg-purple-500',
                                    ]">{{ key }}</button>
                                </div>
                            </div>

                            <form method="post" @keydown.enter.prevent @submit.prevent="addProduct" class="flex justify-center mt-5">
                                <button class="p-2 bg-black rounded-2xl hover:bg-gray-700 transition">Подтвердить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.height-calc{
    height: calc(100vh - (70px + 50px + 70px));
}
</style>
