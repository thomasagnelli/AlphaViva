<template>
<tr>
  <td class="cursor-pointer"><a href="#" @click.prevent="$emit('showLotesQuadra', quadra)">{{quadra.name}}</a></td>
  <td>{{quadra.lotes.length}}</td>
  <td>{{quadra.description}}</td>
  <td class="flex">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input cursor-pointer" v-model="quadra.active" :id="`switchActiveQuadra${quadra.id}`">
        <label  @click="activeQuadra(quadra)" class="custom-control-label cursor-pointer" :for="`switchActiveQuadra${quadra.id}`"></label>
      </div>
      <span :data-target="`#modalUpdateQuadra${quadra.id}`" data-toggle="modal" class="badge cursor-pointer bg-info p-1"><i class="fas fa-pen-alt"></i></span>
      <span :data-target="`#modalDeleleteQuadra${quadra.id}`" data-toggle="modal" class="badge cursor-pointer bg-danger p-1 ml-2"><i class="fas fa-trash"></i></span>
  </td>

   <Form 
    :unidadeId="unidadeId"
    :quadra="quadra"
    :mode="'update'"
    :modalId="`modalUpdateQuadra${quadra.id}`"
    @update="$emit('update')"
  />

  <Modal :title="'Excluir Quadra'" :id="`modalDeleleteQuadra${quadra.id}`">
    <template slot="body">
      <p>Deseja realmente Excluir a quadra {{quadra.name}}?</p>
    </template>
    <template slot="action">
      <button data-dismiss="modal" @click.prevent="deleteQuadra(quadra)" type="button" class="btn btn-danger">Excluir</button>
    </template>
  </Modal>
</tr>
</template>
<script>
import Form from './quadras-form'
import Modal from './../../Modal'
import Swal from 'sweetalert2'

export default {
  name: 'quadras-table-item',
  props:{
    unidadeId: Number,
    quadra: Object,
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
    activeQuadra(quadra){
      setTimeout(() => {
        let inputSwitch = document.querySelector(`#switchActiveQuadra${quadra.id}`)
        let active = false
        if (inputSwitch.checked === true){
          active = true
        }

        axios.patch(`/quadra`, {...quadra, active: active})
        .then((response) => {
          if(response.data.active){
            quadra.active = true
            return
          }
          quadra.active = false
        })
        .catch(() => {
          this.$emit('delete', quadra)
        }) 
      }, 5)
    },
    deleteQuadra(quadra){
      axios.delete(`/quadra`, {params: {id: quadra.id}})
      .then((response) => {
        if(response.data.success) {
          this.$emit('delete', quadra)
          this.toast.fire({
            icon: 'success',
            title: `Quadra Excluida com sucesso!`
          })
          return 
        }

        this.toast.fire({
          icon: 'error',
          title: `Ocorreu um erro ao Excluir a Quadra!`
        })
      })
      .catch((error) => {
        if (error && error.response.data.success === false) {
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