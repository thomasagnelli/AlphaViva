<template>
<Modal :id="modalId" :title="modalTitle">
  <template slot="body">
    <form @submit.prevent="submitForm()" autocomplete="off">
      <div class="row">
        <div class="form-group col-12">
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

        <div class="form-group col-12">
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
          <label for="roleId">Permissão</label>
          <select
            v-model.number="$v.roleId.$model" 
            class="form-control"  id="roleId"
            :class="{'is-invalid': $v.roleId.$error || errors.roleId, 'is-valid': !$v.roleId.$invalid && mode !== 'show'}"
            :disabled="mode === 'show' || (usuario && roles[usuario.roleId] === 'Cliente')"
          >
            <option :value="null" >Selecione</option>
            <option v-for="(role, r) in roles" :key="r" :value="r" >{{role}}</option>
          </select>
        </div>
      </div>
    </form>
  </template>

  <template slot="action">
    <button v-if="mode !== 'show'" @click.prevent="submitForm()" type="submit" class="btn btn-primary">Salvar</button>
  </template>
</Modal>
</template>
<script>
import axios from 'axios'
import { required, minLength, email, numeric} from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
import Swal from 'sweetalert2'
import Modal from './../Modal'

export default {
  name: 'usuarios-form',
  components:{
    Modal
  },
  props: {
    usuario: {
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
    roleId: {
      required,
      numeric
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
      roles: {
        1: "Administrador",
        2: "Gerente",
        3: "Cliente",
        4: "Parceiro",
      },
      errors: [],
      id: null,
      name: '',
      email: '',
      roleId: null,
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

        axios({
          url: `/usuario`,
          method: this.mode === 'create' ? 'post' : 'patch' ,
          data: this.removeTrashPayload(payload)
        })
        .then(() => {
          this.toast.fire({
            icon: 'success',
            title: `Usuário ${this.mode === 'create' ? 'criado' : 'atualizado'} com sucesso!`
          })
          
          this.$emit('cancel')
          this.$emit('update')

          if(this.mode === 'create') {
            this.resetForm()
          }
          document.querySelector(`#close${this.modalId}`).click()
          this.errors = []
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
            title: `Ocorreu um erro ao ${this.mode === 'create' ? 'criar' : 'atualizar'} o Usuário!`
          })
        })
      }
    },
    removeTrashPayload(payload){
      delete payload.toast
      delete payload.modalTitle
      delete payload.roles
      delete payload.errors

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
    }
  },
  beforeMount(){
    if(this.mode === 'create') {
      this.modalTitle = "Novo Usuário"
    }

    if(this.mode === 'update') {
      this.modalTitle = "Editar Usuário"
    }

     if(this.mode === 'show') {
      this.modalTitle = this.usuario.name
    }
  },
  mounted(){
    if (this.usuario) {
      this.id = this.usuario.id
      this.name = this.usuario.name
      this.email = this.usuario.email
      this.roleId = this.usuario.roleId
    }
  }
}
</script>