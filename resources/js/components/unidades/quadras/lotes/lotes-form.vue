<template>
<Modal :id="modalId" :title="modalTitle">
  <template slot="body">
    <form @submit.prevent="submitForm()" autocomplete="off">
      <div class="row">
        <div class="form-group col-12">
          <label for="name">Nome</label>
          <input 
            v-model.trim="$v.name.$model" 
            type="text" 
            class="form-control"
            :class="{'is-invalid': $v.name.$error, 'is-valid': !$v.name.$invalid}"
          >
        </div>
      
        <div class="form-group col-12">
          <label for="description">Descrição</label>
          <textarea 
            v-model.trim="$v.description.$model" 
            class="form-control" 
          :class="{'is-invalid': $v.description.$error, 'is-valid': !$v.description.$invalid}"
          ></textarea>
        </div>
      </div>
    </form>
  </template>

  <template slot="action">
    <button @click.prevent="submitForm()"  data-dismiss="modal" type="submit" class="btn btn-primary">Salvar</button>
  </template>
</Modal>
</template>
<script>
import axios from 'axios'
import { required, minLength, maxLength} from 'vuelidate/lib/validators'
import { validationMixin } from 'vuelidate'
import Swal from 'sweetalert2'
import Modal from './../../../Modal'

export default {
  name: 'lotes-form',
  components:{
    Modal
  },
  props: {
    lote: {
      type: Object,
      default(){
        return null
      }
    },
    mode: String,
    quadraId: Number,
    modalId: String,
  },
  mixins: [validationMixin],
  validations: {
    name: {
      required,
      minLength: minLength(1)
    },
    description: {
      required,
      minLength: minLength(2),
      maxLength: maxLength(100)
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
      id: null,
      name: '',
      description: '',
      status: 1,
    }
  },
  methods:{
    isValidForm(){
      return !this.$v.$invalid
    },
    submitForm(){
      if (!this.$v.$invalid) {
        let payload = {
          ...this.$data,
          unidadeId: this.unidadeId,
          quadraId: this.quadraId,
        }
        delete payload.toast

        axios({
          url: `/lote`,
          method: this.mode === 'create' ? 'post' : 'patch' ,
          data: payload
        })
        .then(() => {
          this.toast.fire({
            icon: 'success',
            title: `Lote ${this.mode === 'create' ? 'criado' : 'atualizado'} com sucesso!`
          })
          
          this.$emit('cancel')
          this.$emit('update')

          if(this.mode === 'create') {
            this.resetForm()
            return
          }
          this.$v.$reset
        })
        .catch(() => {
          this.toast.fire({
            icon: 'error',
            title: `Ocorreu um erro ao ${this.mode === 'create' ? 'criar' : 'atualizar'} o lote!`
          })
        })
      } else{
        this.$v.$touch()
        this.toast.fire({
          icon: 'error',
          title: `Fomulário inválido, favor preencha todos os campos obrigatórios!`
        })
      }
    },
    resetForm(){
      this.id = null
      this.name = ''
      this.description = ''
      this.status = 1
      this.$v.$reset
    }
  },
  beforeMount(){
    if(this.mode === 'create') {
      this.modalTitle = "Novo Lote"
    }

    if(this.mode === 'update') {
      this.modalTitle = "Editar Lote"
    }
  },
  mounted(){
    this.$nextTick(() => {
      this.$v.$reset
    })
    
    if (this.lote) {
      this.id = this.lote.id
      this.name = this.lote.name
      this.status = this.lote.status
      this.description = this.lote.description
    }
  }
}
</script>