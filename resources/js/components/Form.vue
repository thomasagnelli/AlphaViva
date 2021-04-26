<template>
<form :id="`app-form-${name}`" @submit="submitForm($event)" >
  <input v-if="token" type="hidden" name="_token" :value="token">
  <template  v-for="(input, i) in inputs">
      <form-input
        :key="i"
        :name="input.name"
        :type="input.type"
        :autofocus="input.autofocus ? input.autofocus : false"
        :required="input.required ? input.required : false"
        :autocomplete="input.type ? input.type : false"
        :placeholder="input.placeholder"
        :value="input.value"
        :class="input.classes"
        :groupClasses="input.groupClasses"
      />
  </template>
   <slot name="submit" >
      <form-input
        :key="i"
        :name="input.name"
        :type="input.type"
        :autofocus="input.autofocus ? input.autofocus : false"
        :required="input.required ? input.required : false"
        :autocomplete="input.type ? input.type : false"
        :placeholder="input.placeholder"
        :value="input.value"
        :class="input.classes"
        :groupClasses="input.groupClasses"
      />
   </slot>
</form>
</template>
<script>
import axios from 'axios'

export default {
  name: 'Form',
  props: {
    name: '',
    method: 'POST',
    action: '',
    token: null,
    ajax: true,
    inputs: {
      type: Array,
      default: () => {return []}
    },
    submit: {
      type: String,
      default: 'Salvar',
      onSubmit: {
        type: Function,
        default: (event) => {
          this.submitForm(event)
        }
      }
    },
    cancel: {
      type: String,
      default: 'Cancelar' 
    },
    onSubmit:Function,
    onCancel: Function,
  },
  data(){
    return {
      form: {},
      formEl: null,
    }
  },
  beforeMount(){
    this.inputs.forEach((input) => {
      this.form[input.name] = input.value
    })
  },
  methods:{
    submitForm(event){
      console.log
    }
  },
}
</script>