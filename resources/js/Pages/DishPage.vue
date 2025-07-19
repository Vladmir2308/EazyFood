<script setup>
import {Head} from "@inertiajs/vue3";
import AddableList from "@/Components/AddableList.vue";
import MainButton from "@/Components/MainButton.vue";
import {ref, watch} from "vue";
import { v4 as uuidv4 } from 'uuid'
import PlusIcon from "@/Components/Svg/PlusIcon.vue";
import FieldsStandartBlock from "@/Components/FieldsStandartBlock.vue";
import FieldsStandartBlockItem from "@/Components/FieldsStandartBlockItem.vue";
import SelectStandartField from "@/Components/SelectStandartField.vue";
import InputStandart from "@/Components/InputStandart.vue";
import {debounce} from "lodash";
import {onKeyUp} from "@vueuse/core";

const props = defineProps({
    types: null,
    user_id: null
})

const unitBtns = ['г', 'мл', 'шт']

const dish = ref({
    name: null,
    user_id: props.user_id,
    type_id: 1,
    products: [
        {
            id: uuidv4(),
            name: null,
            amount: null,
            unit: null,
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
    dish.value.products.push({
        id: uuidv4(),
        name: null,
        amount: null,
        unit: null,
    })
}
const deleteProductFieldRow =  (id) => {
    if(dish.value.products.length === 1)
        return

    dish.value.products = dish.value.products.filter(item => item.id !== id)
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
                if(item.name === name){
                    dish.value.products.find(item => item.id === currentInputId.value).unit = item.default_unit
                }
            })
        }
        else
            dish.value.products.find(item => item.id === currentInputId.value).unit = ''

        console.log(suggestionProducts.value)

        suggestionProductsShowStatus.value = false
        currentInputId.value = null
        suggestionProducts.value = []
    }, 200)
}

const selectSuggestionProduct = (name, rowId) => {
    const currentRow = dish.value.products.find(item => item.id === rowId)

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
        const currentProduct = dish.value.products.find(item => item.id === inputId)

        if (currentProduct) {
            const fullName = suggestionProducts.value[0].name
            typeAutocomplete(currentProduct, fullName, currentProduct.name)
        }

        suggestionProductsShowStatus.value = false
    }
}
/* ... */

/* Product Unit */
const selectProductUnit = (unitName) => {

}
/* ... */

onKeyUp('Enter', (e) => {
    if(e.altKey)
        addProductFieldRow()
})

</script>

<template>
    <Head title="Блюда"/>

    <AddableList :visible="addableList"
                 @close-addable-list="addableList = false"
                 header-title="Добавь свое блюдо"
    >
        <FieldsStandartBlock>
            <div class="flex flex-col gap-8">
                <FieldsStandartBlockItem title="Название" v-model="dish.name"/>

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
                        <InputStandart type="search"
                                       placeholder="Помидор"
                                       custom-class="input__standart--md"
                                       v-model="row.name"
                                       @focus="showSuggestionProducts(row.id)"
                                       @blur="hideSuggestionProducts(row.name, row.id)"
                                       @keydown.enter.prevent="autocompleteFirstProduct(row.id)"
                                       @input="searchProduct(row.name, row.id)"
                        />

                        <transition name="fade-down" v-auto-animate>

                            <ul
                                v-if="suggestionProductsShowStatus && currentInputId === row.id && suggestionProducts.length"
                                class="fields-product__suggestions"
                            >
                                <li class="fields-product__suggestions-item"
                                    v-for="item in suggestionProducts"
                                    :key="item.id"
                                    @click="selectSuggestionProduct(item.name, row.id)"
                                >
                                    {{ item.name }}
                                </li>
                            </ul>
                        </transition>

                        <InputStandart type="number"
                                       placeholder="200"
                                       custom-class="input__standart--sm"
                                       v-model="row.amount"
                        />
                        <div class="fields-product__buttons">
                            <div class="fields-product__buttons-unit">
                                <button v-for="unit in unitBtns" :key="unit"
                                        @click="selectProductUnit(unit)"
                                        :class="['btn-fields__product-unit', row.unit === unit ? 'active' : '']"
                                >{{ unit  }}</button>
                            </div>
                            <div class="fields-product__buttons-delete"
                                 @click="deleteProductFieldRow(row.id)"
                            >
                                <button class="fields-product__buttons-delete__btn">✕</button>
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
