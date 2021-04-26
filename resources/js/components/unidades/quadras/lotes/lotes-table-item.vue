<template>
<tr v-if="lote">
  <td>{{lote.name}}</td>
  <td>{{lote.description}}</td>
  <td><span :class="`badge ${status[reserva ? reserva.status : 0].class}`">{{status[reserva ? reserva.status : 0].name}}</span></td>
  <td class="flex">
      <span :data-target="`#modalUpdateLote${lote.id}`" data-toggle="modal" class="badge cursor-pointer bg-info p-1"><i class="fas fa-pen-alt"></i></span>
      <span v-if="!reserva" :data-target="`#modalDeleleteLote${lote.id}`" data-toggle="modal" class="badge cursor-pointer bg-danger p-1 ml-2"><i class="fas fa-trash"></i></span>
  </td>

  <Form
    :quadraId="quadraId"
    :lote="lote"
    :mode="'update'"
    :modalId="`modalUpdateLote${lote.id}`"
    @update="$emit('update')"
  />

  <Modal v-if="!reserva" :title="'Excluir Lote'" :id="`modalDeleleteLote${lote.id}`">
    <template slot="body">
      <p>Deseja realmente Excluir o Lote {{lote.name}}?</p>
    </template>
    <template slot="action">
      <button data-dismiss="modal" @click.prevent="deleteLote(lote)" type="button" class="btn btn-danger">Excluir</button>
    </template>
  </Modal>
</tr>
</template>
<script>
import Modal from './../../../Modal'
import Form from './lotes-form'
import Swal from 'sweetalert2'

export default {
  name: 'lotes-table-item',
  props:{
    unidadeId: Number,
    quadraId: Number,
    lote: Object,
    reserva: Object,
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
      status: {
        0: {
          class: "badge-primary",
          name: "Aberto",
        },
        1: {
          class: "badge-success",
          name: "Reservado",
        },
        2: {
          class: "badge-danger",
          name: "Fechado",
        },
        3: {
          class: "badge-dark",
          name: "Outro",
        }
      }
    }
  },
  methods:{
    deleteLote(lote){
      axios.delete(`/lote`, {params: {id: lote.id}})
      .then((response) => {
        if(response.data.success) {
          this.$emit('delete', lote)
          this.toast.fire({
            icon: 'success',
            title: `Lote Excluido com sucesso!`
          })
          return 
        }

        this.toast.fire({
          icon: 'error',
          title: `Ocorreu um erro ao Excluir o Lote!`
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