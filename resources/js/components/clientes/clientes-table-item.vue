<template>
<tr>
  <td><a :data-target="`#modalShowCliente${cliente.id}`" data-toggle="modal" href="#"><i class="far fa-user"></i></a></td>
  <td><a :data-target="`#modalShowCliente${cliente.id}`" data-toggle="modal" href="#">{{cliente.user.name}}</a></td>
  <td class="flex">
    <span :data-target="`#modalUpdateCliente${cliente.id}`" data-toggle="modal" class="badge cursor-pointer bg-info p-1"><i class="fas fa-pen-alt"></i></span>
    <span :data-target="`#modalDeleleteCliente${cliente.id}`" data-toggle="modal" class="badge cursor-pointer bg-danger p-1 ml-2"><i class="fas fa-trash"></i></span>
  </td>

  <Form 
    :cliente="cliente"
    :mode="'show'"
    :modalId="`modalShowCliente${cliente.id}`"
  />
  
  <Form 
    :cliente="cliente"
    :mode="'update'"
    :modalId="`modalUpdateCliente${cliente.id}`"
    @update="$emit('update')"
  />

  <Modal :title="'Excluir Cliente'" :id="`modalDeleleteCliente${cliente.id}`">
    <template slot="body">
      <p>Deseja realmente Excluir o Cliente {{cliente.name}}?</p>
    </template>
    <template slot="action">
      <button data-dismiss="modal" @click.prevent="deleteCliente(cliente)" type="button" class="btn btn-danger">Excluir</button>
    </template>
  </Modal>
</tr>
</template>
<script>
import Modal from './../Modal'
import Form from './clientes-form-modal'
import Swal from 'sweetalert2'

export default {
  name: 'clientes-table-item',
  props:{
    cliente: Object
  },
  components: {
    Modal,
    Form
  },
  data() {
    return {
      toast: Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      }),
    }
  },
  methods:{
    deleteCliente(cliente){
      axios.delete(`/cliente`, {params: {id: cliente.id}})
      .then((response) => {
        if(response.data.success) {
          this.$emit('delete', cliente)
          this.toast.fire({
            icon: 'success',
            title: `Cliente Excluido com sucesso!`
          })
          return 
        }

        this.toast.fire({
          icon: 'error',
          title: `Ocorreu um erro ao Excluir o Cliente!`
        })
      })
      .catch((error) => {
        if (error.response.data.success === false) {
          this.toast.fire({
            icon: 'error',
            title: error.response.data.error
          })
        }
      })
    }
  }
}
</script>