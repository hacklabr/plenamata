<template>
    <div class="custom-drop free-label keep-drop-inside" :class="{ filled: value !== '', opened: activeField === id, locked: locked === true }" @click="toggle" @keypress.enter="toggle">
        <em>
            <button class="toggle" :class="triggerClass" type="button" tabindex="0" :disabled="locked === true">
                <i :class="icon"></i>
                <strong><span>{{getLabel()}}</span></strong>
            </button>
        </em>
        <div class="custom-drop--options">
            <div>
                <strong>{{placeholder}}</strong>
                <button type="button" data-action="close">Fechar</button>
            </div>
            <div class="list-wrapper">
                <ul>
                    <li v-if="value !== '' && placeholder">
                        <input type="radio" :id="id + '-all'" :name="id" value="" v-model="valueModel">
                        <label :for="id + '-all'" :title="placeholder" @click="close" @keypress.enter="close">{{placeholder}}</label>
                    </li>
                    <li v-for="option of options">
                        <input type="radio" :id="getID(option)" :name="id" :value="option[keyId]" v-model="valueModel">
                        <label :for="getID(option)" :title="option[keyLabel]" @click="close" @keypress.enter="close">{{option[keyLabel]}}</label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>

    const { DateTime } = window.luxon

    import { vModel } from '../../utils/vue'
    
    export default {
        name: 'Dropdown',
        props: {
            id : { type: String, required: true },
            options: { type: [ Object, Array ], required: true },
            keyId: { type: String, required: true },
            keyLabel: { type: String, required: true },
            triggerClass: { type: String, default: 'inline' },
            icon: { type: [ String, Boolean ], default: '' },
            placeholder : { type: [ String, Boolean ], default: false },
            value : { type: [ String, Number, Boolean ], default: null },
            title : { type: [ String, Boolean ], default: false },
            locked : { type: Boolean, default: false },
            activeField : { default: '' }
        },
        data () {
            return {
                html_id : '',
                showFloater : false
            }
        },
        methods : {
            getID( option ){
                return this.html_id = String( this.id ) + '-' + String( option[ this.keyId ] );
            },
            getLabel(){

                // Empty value
                if( this.value === '' ) return this.placeholder;

                // Keys
                const keys = 
                    typeof( this.options ) === 'object' 
                    ? Object.keys( this.options ) 
                    : this.options.keys
                ;

                // Return label
                let return_label = this.placeholder;
                for( let ki = 0; ki < keys.length; ki++ ){
                    const option = this.options[ keys[ ki ] ];
                    if( option[ this.keyId ] === this.value ){
                        return_label = option[ this.keyLabel ];
                    }
                }

                return return_label;

            },
            getValue(){
                if( this.value === '' ){
                    return Object.keys(this.options)[0];
                }
                else {
                    return this.value;
                }
            },
            generateUID(){
                // I generate the UID from two parts here 
                // to ensure the random number provide enough bits.
                var firstPart = (Math.random() * 46656) | 0;
                var secondPart = (Math.random() * 46656) | 0;
                firstPart = ( '000' + firstPart.toString(36)).slice(-3);
                secondPart = ( '000' + secondPart.toString(36)).slice(-3);
                return firstPart + secondPart;
            },
            toggle(){
                this.$emit( 'update:activeField', ( this.activeField === this.id ? '' : this.id ) );
            },
            close(){
                this.$emit( 'update:activeField', '' );
            },
            open(){
                this.$emit( 'update:activeField', this.id );
            },
        },
        computed: {
            valueModel: vModel('value'),
            previousMonth () {
                const month = this.date.month
                return months[month]
            }
        },
    }
</script>
