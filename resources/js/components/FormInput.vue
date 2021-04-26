<template>
<div class="input-group" :class="groupClasses">
  <input
    class="form-control"
    :type="type"
    :autofocus="autofocus"
    :required="required"
    :autocomplete="autocomplete"
    :placeholder="placeholder"
    :name="name"  
    :id="name" 
    :value="value"
    :class="classes + {'is-invalid': error}"
    v-model="inputValue"
    @focus="updateValue()"
    @input="updateValue()"
    @blur="updateValue()"
    :aria-invalid="error ? true : false"
    :aria-describedby="`error-${name}`"
  />

  <div v-if="icon" class="input-group-append">
    <div class="input-group-text">
      <span :class="icon"></span>
    </div>
  </div>

  <span v-if="error" :id="`error-${name}`" class="error invalid-feedback">{{error}}</span>
</div>
</template>
<script>
export default {
  props: {
    name: {
      type: String,
      default: 'name'
    },
    type: {
      type: String,
      default: 'text'
    },
    required: {
      type: Boolean,
      default: false
    },
    autofocus: {
      type: Boolean,
      default: false
    },
    autocomplete: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    icon: {
      type: String,
      default: 'fas fa-file-signature'
    },
    classes: {
      type: String,
      default: 'fas fa-file-signature'
    },
    groupClasses:{
      type: String,
      default: 'mb-3'
    },
    error: {
      type: String,
      default: null
    },
    value: null,
  },
  data(){
    return {
      inputValue: null,
    }
  },
  methods:{
    updateValue(){
      this.$emit("updateValue", {name: this.name, value: this.inputValue})
    }
  }
}
</script>