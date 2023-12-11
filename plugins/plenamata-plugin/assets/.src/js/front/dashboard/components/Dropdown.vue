<template>
    <div class="custom-drop free-label keep-drop-inside"
        :class="{ filled: value !== '', locked: disabled, opened: activeField === id }" @click="toggle"
        @keypress.enter="toggle">
        <em>
            <button class="toggle" :class="triggerClass" type="button" tabindex="0" :disabled="disabled">
                <i :class="icon" />
                <slot name="tooltip" />
                <strong><span>{{ label }}</span></strong>
            </button>
        </em>
        <div class="custom-drop--options">
            <div>
                <strong>{{ placeholder }}</strong>
                <button type="button" data-action="close">Fechar</button>
            </div>
            <div class="list-wrapper">
                <ul>
                    <li v-if="placeholder">
                        <input type="radio" :id="id + '-all'" :name="id" value="" v-model="valueModel">
                        <label data-label="essa" :for="id + '-all'" :title="placeholder" @click="close"
                            @keypress.enter="close">{{ placeholder
                            }}</label>
                    </li>
                    <li v-for="option of options" :key="getID(option)">
                        <input type="radio" :id="getID(option)" :name="id" :value="option[keyId]" v-model="valueModel">
                        <label :for="getID(option)" :title="option[keyLabel]" @click="close" @keypress.enter="close">{{
                            option[keyLabel] }}</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { vModel } from '../../utils/vue'
import Tooltip from './Tooltip.vue'

export default {
    name: 'Dropdown',
    props: {
        activeField: { default: '' },
        disabled: { type: Boolean, default: false },
        icon: { type: [Boolean, String], default: '' },
        id: { type: String, required: true },
        keyId: { type: String, required: true },
        keyLabel: { type: String, required: true },
        options: { type: [Array, Object], required: true },
        placeholder: { type: [Boolean, String], default: false },
        triggerClass: { type: String, default: 'inline' },
        title: { type: [Boolean, String], default: false },
        value: { type: [Boolean, Number, String], default: null },
        onEmptyClick: { default: false },
        errorMessage: { default: false }
    },
    components: {
        Tooltip
    },
    model: {
        prop: 'value',
        event: 'update:value',
    },
    computed: {
        label() {
            if (this.value === '' || (typeof this.value != 'string' && typeof this.value != 'number')) {
                console.log('aqui', this.value, typeof this.value )
                return this.title
            }
            console.log(this.value);

            const keys = (typeof this.options === 'object') ? Object.keys(this.options) : this.options.keys

            let returnLabel = this.placeholder
            for (let i = 0; i < keys.length; i++) {
                const option = this.options[keys[i]]
                if (String(option[this.keyId]) === String(this.value)) {
                    returnLabel = option[this.keyLabel]
                }
            }
            return returnLabel
        },
        valueModel: vModel('value'),
    },
    methods: {
        close() {
            this.$emit('update:activeField', '')
        },
        generateUID() {
            // I generate the UID from two parts here
            // to ensure the random number provide enough bits.
            let firstPart = (Math.random() * 46656) | 0
            let secondPart = (Math.random() * 46656) | 0
            firstPart = ('000' + firstPart.toString(36)).slice(-3)
            secondPart = ('000' + secondPart.toString(36)).slice(-3)
            return firstPart + secondPart
        },
        getID(option) {
            return `${this.id}-${option[this.keyId]}`
        },
        open() {
            this.$emit('update:activeField', this.id)
        },
        toggle() {
            if (this.options.length == 0 && this.onEmptyClick) {
                this.onEmptyClick();
            }
            this.$emit('update:activeField', (this.activeField === this.id ? '' : this.id))
        },
    },
}
</script>
