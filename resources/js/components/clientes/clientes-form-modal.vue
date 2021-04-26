<template>
<Modal :id="modalId" :title="modalTitle" :showFooter="false">
  <template slot="body">
    <Form @submitForm="handleSubmitForm($event)" :cliente="cliente" :mode="mode"/>
  </template>

  <template slot="action">
    <button v-if="mode !== 'show'" @click.prevent="submitForm()" type="submit" class="btn btn-primary">Salvar</button>
  </template>
</Modal>
</template>
<script>
import Modal from './../Modal'
import Form from './clientes-form'

export default {
  name: 'clientes-form-modal',
  components:{
    Modal,
    Form
  },
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
  data(){
    return {
      modalTitle: 'Cliente',
    }
  },
  methods: {
    handleSubmitForm(event){
      this.$emit('update', event)
      window.$(`#${this.modalId}`).modal('hide')
    },
    submitForm(){
      this.$emit('submitFormCliente')
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
  }
}
</script>