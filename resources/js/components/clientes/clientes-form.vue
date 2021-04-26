<template>
<form @submit.prevent="submitForm()" autocomplete="off">
  <div class="row">
    <div class="form-group col-12 col-md-6">
      <label for="name">Nome</label>
      <input
       id="name"
        v-model.trim="$v.name.$model" 
        type="text" 
        class="form-control"
        :class="{'is-invalid': $v.name.$error || errors.name, 'is-valid': !$v.name.$invalid && mode !== 'show'}"
        :disabled="mode === 'show'"
      >
      <template v-if="errors.name">
        <span v-for="(error,e) in errors.name" :key="e" class="error invalid-feedback" style="display: inline;">{{error}}</span>
      </template>
    </div>

    <div class="form-group col-12 col-md-6">
      <label for="email">Email</label>
      <input
        id="email"
        v-model.trim="$v.email.$model" 
        type="email" 
        class="form-control"
        :class="{'is-invalid': $v.email.$error || errors.email, 'is-valid': !$v.email.$invalid && mode !== 'show'}"
        :disabled="mode === 'show'"
      >
      <template v-if="errors.email">
        <span v-for="(error,e) in errors.email" :key="e" class="error invalid-feedback" style="display: inline;">{{error}}</span>
      </template>
    </div>

    <div class="form-group col-12">
      <label for="documentType">Tipo de Pessoa</label>
      <select
        v-model="$v.documentType.$model" 
        class="form-control"  id="documentType"
        :disabled="mode === 'show'"
      >
        <option value="cpf" >Pessoa Física</option>
        <option value="cnpj" >Pessoa Jurídica</option>
      </select>
    </div>

    <div v-if="documentType === 'cpf'" class="form-group col-12 col-md-6">
      <label for="cpf">CPF</label>
      <input 
        id="cpf"
        v-model="$v.document.$model"
        type="text"
        placeholder="000.000.000-00"
        v-mask="'###.###.###-##'"
        class="form-control"
        :class="{'is-invalid': $v.document.$error || errors.cpf, 'is-valid': !$v.document.$invalid && mode !== 'show'}"
        :disabled="mode === 'show'"
      >
      <template v-if="errors.cpf">
        <span v-for="(error,e) in errors.cpf" :key="e" class="error invalid-feedback" style="display: inline;">{{error}}</span>
      </template>
    </div>

     <div v-if="documentType === 'cnpj'" class="form-group col-12 col-md-6">
      <label for="cnpj">CNPJ</label>
      <input 
        id="cnpj"
        placeholder="XX.XXX.XXX/0001-XX"
        v-mask="'##.###.###/####-##'"
        v-model="$v.document.$model"
        type="text" 
        class="form-control"
        :class="{'is-invalid': $v.document.$error || errors.cnpj, 'is-valid': !$v.document.$invalid && mode !== 'show'}"
        :disabled="mode === 'show'"
      >
      <template v-if="errors.cnpj">
        <span v-for="(error,e) in errors.cnpj" :key="e" class="error invalid-feedback" style="display: inline;">{{error}}</span>
      </template>
    </div>

    <div class="form-group col-12 col-md-6">
      <label for="phone">Fone</label>
      <input 
        id="phone"
        v-model.trim="$v.phone.$model" 
        type="text"
        placeholder="(00) 000000000"
        v-mask="'(##) #########'"
        class="form-control"
        :class="{'is-invalid': $v.phone.$error, 'is-valid': !$v.phone.$invalid && mode !== 'show'}"
        :disabled="mode === 'show'"
      >
    </div>
  </div>

  <button v-if="mode !== 'show'" @click.prevent="submitForm()" type="submit" class="btn btn-primary float-right">Salvar</button>
</form>
</template>
<script>
import axios from 'axios'
import { required, minLength, maxLength, or, email} from 'vuelidate/lib/validators'
import { cnpj, cpf } from 'cpf-cnpj-validator';
import { validationMixin } from 'vuelidate'
import Swal from 'sweetalert2'

export default {
  name: 'clientes-form',
  props: {
    cliente: {
      type: Object,
      default(){
        return null
      }
    },
    mode: String,
    modalId: String,
  },
  mixins: [validationMixin],
  validations: {
    name: {
      required,
      minLength: minLength(1)
    },
    email: {
      required,
      email
    },
    documentType: {
      required,
    },
    document: {
      required,
      customVal: or(
        (value, vm) => vm.$v.documentType.$model === 'cpf' && cpf.isValid(value), 
        (value, vm) => vm.$v.documentType.$model === 'cnpj' && cnpj.isValid(value)
      )
    },
    phone: {
      required,
      minLength: minLength(13),
      maxLength: maxLength(14)
    },
  },
  data(){
    return {
      modalTitle: '',
      toast: Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      }),
      errors: [],
      id: null,
      name: '',
      email: '',
      phone: '',
      document: '',
      documentType: 'cpf',
    }
  },
  methods:{
    isValidForm(){
      return !this.$v.$invalid
    },
    submitForm(){
      this.$v.$touch()
      if (!this.$v.$invalid) {
        let payload = {
          ...this.$data,
        }
        payload[this.documentType] = this.document

        axios({
          url: `/cliente`,
          method: this.mode === 'create' ? 'post' : 'patch' ,
          data: this.removeTrashPayload(payload)
        })
        .then((response) => {
          this.toast.fire({
            icon: 'success',
            title: `Cliente ${this.mode === 'create' ? 'criado' : 'atualizado'} com sucesso!`
          })

          if(this.mode === 'create') {
            this.resetForm()
          }
          this.errors = []
          this.$emit('submitForm', response.data)
        })
        .catch((error) => {
          if (error.response.data.error) {
            return this.toast.fire({
              icon: 'error',
              title: error.response.data.error
            })
          }

          if (error.response.data.errors) {
            this.errors = error.response.data.errors
            return
          }

          this.toast.fire({
            icon: 'error',
            title: `Ocorreu um erro ao ${this.mode === 'create' ? 'criar' : 'atualizar'} o Cliente!`
          })
          this.$emit('submitForm', null)
        })
      }
    },
    removeTrashPayload(payload){
      delete payload.toast
      delete payload.modalTitle
      delete payload.document
      delete payload.documentType

      if (this.mode === 'create') {
        delete payload.id
      }
      return payload
    },
    resetForm(){
      this.id = null
      this.name = ''
      this.email = ''
      this.phone = ''
      this.document = ''
      this.documentType = 'cpf'
      this.$v.$reset
    }
  },
  beforeMount(){
    if(this.mode === 'create') {
      this.modalTitle = "Novo Cliente"
    }

    if(this.mode === 'update') {
      this.modalTitle = "Editar Cliente"
    }

     if(this.mode === 'show') {
      this.modalTitle = this.cliente.user.name
    }
  },
  mounted(){
    this.$v.$reset

    if (this.cliente) {
      this.id = this.cliente.id
      this.name = this.cliente.user.name
      this.email = this.cliente.user.email
      this.phone = this.cliente.phone

      if(this.cliente.cpf) {
        this.document = this.cliente.cpf
        this.documentType = 'cpf'
        return
      }

      if(this.cliente.cnpj) {
        this.document = this.cliente.cnpj
        this.documentType = 'cnpj'
      }
    }
    
    this.$root.$on('submitFormCliente', () => {this.submitForm()})
    this.$on('submitFormCliente', () => {this.submitForm()})
  }
}
</script>