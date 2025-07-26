<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import AddableList from "@/Components/AddableList.vue";
import MainButton from "@/Components/MainButton.vue";
import {nextTick, onBeforeUnmount, onMounted, reactive, ref, watch} from "vue";
import { v4 as uuidv4 } from 'uuid'
import PlusIcon from "@/Components/Svg/PlusIcon.vue";
import FieldsStandartBlock from "@/Components/FieldsStandartBlock.vue";
import FieldsStandartBlockItem from "@/Components/FieldsStandartBlockItem.vue";
import SelectStandartField from "@/Components/SelectStandartField.vue";
import InputStandart from "@/Components/InputStandart.vue";
import {debounce} from "lodash";
import {onKeyUp} from "@vueuse/core";
import ListIcon from "@/Components/Svg/ListIcon.vue";
import SelectList from "@/Components/SelectList.vue";

const props = defineProps({
    types: null,
    user_id: null
})

const unitBtns = ['г', 'мл', 'шт']

const dish = useForm({
    name: null,
    user_id: props.user_id,
    type_id: 1,
    products: [
        {
            id: uuidv4(),
            name: null,
            amount: null,
            unit: null,
            category_id: null,
            categoryName: null,
            nameError: false,
            amountError: false,
            categoryListShowStatus: false,
        }
    ],
})

/* Addable List */
const addableList = ref(false)
const showAddableList = () => {
    addableList.value = true
    document.body.style.overflow = 'hidden'
}
/* ... */

/* Product List */
const addProductFieldRow = () => {
    dish.products.push({
        id: uuidv4(),
        name: null,
        amount: null,
        unit: null,
        category_id: null,
        categoryName: null,
        nameError: false,
        amountError: false,
        categoryListShowStatus: false,
    })
}
const deleteProductFieldRow =  (id) => {
    if(dish.products.length === 2)
        return

    dish.products = dish.products.filter(item => item.id !== id)
}
/* ... */

/* Product Search */
const suggestionProducts = ref([])
const suggestionProductsShowStatus = ref(false)

const currentInputId = ref()
const showSuggestionProducts = (inputId) => {
    setTimeout(() => {
        currentInputId.value = inputId
        suggestionProductsShowStatus.value = true
    }, 200)
}
const hideSuggestionProducts = (name) => {
    setTimeout(() => {
        if(suggestionProducts.value){
            suggestionProducts.value.forEach(item => {
                const dishItem = dish.products.find(item => item.id === currentInputId.value)
                if(item.name === name){
                    dishItem.unit = item.default_unit
                    dishItem.categoryName = item.categories[0].name
                    dishItem.category_id = item.categories[0].id

                }
                else{
                    dishItem.categoryName = ''
                    dishItem.category_id = ''
                }
            })
        }
        else
            dish.products.find(item => item.id === currentInputId.value).unit = ''

        suggestionProductsShowStatus.value = false
        currentInputId.value = null
        suggestionProducts.value = []
    }, 200)
}

const selectSuggestionProduct = (name, rowId) => {
    const currentRow = dish.products.find(item => item.id === rowId)

    suggestionProducts.value.forEach(item => {
        if(item.name === name){
            currentRow.unit = item.default_unit
        }
    })

    currentRow.name = name
}

const searchProduct = debounce( async (name) => {
    if(name === ''){
        suggestionProducts.value = ''
        return
    }

    const { data } = await axios.get(route('dish.search.product'), {
        params: { q: name }
    })

    console.log(suggestionProducts.value)

    suggestionProducts.value = data
}, 0)

const typeAutocomplete = async (targetRef, text, from = '') => {
    let current = from
    for (let i = from.length; i < text.length; i++) {
        await new Promise(resolve => setTimeout(resolve, 30)) // скорость печати
        current += text[i]
        targetRef.name = current
    }
}
const autocompleteFirstProduct = (inputId) =>{
    if(suggestionProducts.value.length !== 0){
        const currentProduct = dish.products.find(item => item.id === inputId)

        if (currentProduct) {
            const fullName = suggestionProducts.value[0].name
            typeAutocomplete(currentProduct, fullName, currentProduct.name)
        }

        suggestionProductsShowStatus.value = false
    }
}
/* ... */

/* Product Unit */
const selectProductUnit = (unitName, fieldId) => {
    if(dish.products.find(item => item.id === fieldId).unit === unitName)
        dish.products.find(item => item.id === fieldId).unit = ''
    else
        dish.products.find(item => item.id === fieldId).unit = unitName
}
/* ... */

/* Send Data */
const errors = reactive({
    dishName: false,
})

/* Category Modal */
const openedModalId = ref(null)
const modalRefs = ref({}) // id => DOM-элемент

function setModalRef(id, el) {
    if (el) modalRefs.value[id] = el
    else delete modalRefs.value[id] // при удалении
}
function toggleCategoryList(id) {
    openedModalId.value = openedModalId.value === id ? null : id
}

function handleClickOutside(event) {
    const currentId = openedModalId.value
    const currentModalEl = modalRefs.value[currentId]

    if (currentId && currentModalEl && !currentModalEl.contains(event.target)) {
        openedModalId.value = null
    }
}
/* ... */
const sendDishData = () => {
    let hasError = false

    errors.dishName = !dish.name


    dish.products.forEach(product => {
        // Проверка поля name
        product.nameError = !product.name || product.name.trim() === ''
        // Проверка поля amount
        product.amountError = !product.amount || isNaN(product.amount)

        if (product.nameError || product.amountError) {
            hasError = true
        }
    })

    if(!hasError){
        dish.post(route('dish.store'))
    }
}
/* ... */

onKeyUp('Enter', (e) => {
    if(e.altKey)
        addProductFieldRow()
})

onMounted(() => {
    dish.products.push({
        id: uuidv4(),
        name: null,
        amount: null,
        unit: null,
        category_id: null,
        categoryName: null,
        nameError: false,
        amountError: false,
        categoryListShowStatus: false,
    })

    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
})

</script>

<template>
    <Head title="Блюда"/>

    <AddableList :visible="addableList"
                 @close-addable-list="addableList = false"
                 @send-dish-data="sendDishData"
                 header-title="Добавь свое блюдо"
                 :submit-btn-status="dish.processing"
    >
        <FieldsStandartBlock>
            <div class="flex flex-col gap-8">
                <FieldsStandartBlockItem title="Название" v-model="dish.name" :error-status="errors.dishName"/>

                <SelectStandartField :options="types" v-model="dish.type_id"/>
            </div>
        </FieldsStandartBlock>

        <FieldsStandartBlock title="Укажи продукты">
            <div class="fields-product">
                <div class="fields-product__head">
                    <div class="fields-product__head-name">Название</div>
                    <div class="fields-product__head-name">Количество</div>
                    <div class="fields-product__head-name">Единица</div>
                    <div class="fields-product__head-name"></div>
                </div>

                <div class="flex flex-col gap-3" v-auto-animate>
                    <div class="fields-product__row"
                         v-for="row in dish.products"
                         :key="row.id"
                    >
                        <div class="flex gap-1 items-center relative">
                            <InputStandart type="search"
                                           placeholder="Помидор"
                                           custom-class="input__standart--md"
                                           v-model="row.name"
                                           @focus="showSuggestionProducts(row.id)"
                                           @blur="hideSuggestionProducts(row.name, row.id)"
                                           @keydown.enter.prevent="autocompleteFirstProduct(row.id)"
                                           @input="searchProduct(row.name, row.id)"
                                           :class="{'border-red-700': row.nameError}"
                            />

                            <ListIcon class="list-icon" @click.stop="toggleCategoryList(row.id)"
                                      :class="{'active': openedModalId === row.id}"
                            />

                            <div class="input__modal" v-if="openedModalId === row.id"
                                 :ref="el => setModalRef(row.id, el)"
                            >
                                <h2 class="input__modal-title">Категория</h2>
                                <div class="input__modal-content">
                                    <InputStandart custom-class="input__standart--md" v-model="row.categoryName"/>
                                </div>
                            </div>
                        </div>


                        <transition name="fade-down" v-auto-animate>
                            <SelectList v-if="suggestionProductsShowStatus && currentInputId === row.id && suggestionProducts.length"
                                        :items="suggestionProducts"
                                        @send-suggestion-item="(productName) => selectSuggestionProduct(productName, row.id)"
                            />
                        </transition>

                        <InputStandart type="number"
                                       placeholder="200"
                                       custom-class="input__standart--sm"
                                       v-model="row.amount"
                                       :class="{'border-red-700': row.amountError}"
                        />
                        <div class="fields-product__buttons">
                            <div class="fields-product__buttons-unit">
                                <button v-for="unit in unitBtns" :key="unit"
                                        @click="selectProductUnit(unit, row.id)"
                                        :class="['btn-fields__product-unit', row.unit === unit ? 'active' : '']"
                                >{{ unit  }}</button>
                            </div>
                            <div class="fields-product__buttons-delete"
                                 @click="deleteProductFieldRow(row.id)"
                            >
                                <button class="fields-product__buttons-delete__btn" v-if="dish.products.length > 2">✕</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="btn-fields__dashed-border">
                    <button class="btn-white" @click="addProductFieldRow">Добавить</button>
                </div>
            </div>
        </FieldsStandartBlock>
    </AddableList>

    <div class="dish__header">
        <MainButton :icon="PlusIcon" title="Добавить" @click="showAddableList"/>
    </div>

</template>

<style>
.fade-down-enter-active,
.fade-down-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}

.fade-down-enter-from,
.fade-down-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}

.fade-down-enter-to,
.fade-down-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
