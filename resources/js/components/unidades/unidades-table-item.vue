<template>
<tr>
  <td class="cursor-pointer"><a href="#" @click.prevent="$emit('showQuadrasUnidade', unidade)">{{unidade.name}}</a></td>
  <td>{{unidade.location}}</td>
  <td>{{unidade.description}} </td>
  <td>{{unidade.quadras.length}}</td>

  <td class="flex">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input cursor-pointer" v-model="unidade.active" :id="`switchActiveUnidade${unidade.id}`">
        <label  @click="activeUnidade(unidade)" class="custom-control-label cursor-pointer" :for="`switchActiveUnidade${unidade.id}`"></label>
      </div>
      <span :data-target="`#modalUpdateUnidade${unidade.id}`" data-toggle="modal" class="badge cursor-pointer bg-info p-1"><i class="fas fa-pen-alt"></i></span>
      <span :data-target="`#modalDeleleteUnidade${unidade.id}`" data-toggle="modal" class="badge cursor-pointer bg-danger p-1 ml-2"><i class="fas fa-trash"></i></span>
  </td>

  <Form 
    :modalId="`modalUpdateUnidade${unidade.id}`" 
    :unidade="unidade" 
    :mode="'update'" 
    @update="$emit('update')"
  />
  
  <Modal :title="'Excluir Unidade'" :id="`modalDeleleteUnidade${unidade.id}`">
    <template slot="body">
      <p>Deseja realmente Excluir a unidade {{unidade.name}}?</p>
    </template>
    <template slot="action">
      <button data-dismiss="modal" @click.prevent="deleteUnidade(unidade)" type="button" class="btn btn-danger">Excluir</button>
    </template>
  </Modal>
</tr>
</template>
<script>
import Modal from './../Modal'
import Swal from 'sweetalert2'
import Form from './unidades-form'

export default {
  name: 'unidades-table-item',
  props:{
    unidade: Object
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
      showQuadras: false,
    }
  },
  methods:{
    showQuadrasUnidade(){
      this.showQuadras = true
    },
    activeUnidade(unidade){
      setTimeout(() => {
        let inputSwitch = document.querySelector(`#switchActiveUnidade${unidade.id}`)
        let active = false
        if (inputSwitch.checked === true){
          active = true
        }

        axios.patch(`/unidade`, {...unidade, active: active})
        .then((response) => {
          if(response.data.active){
            unidade.active = true
            return
          }
          unidade.active = false
        })
      }, 5)
    },
    deleteUnidade(unidade){
      axios.delete(`/unidade`, {params: {id: unidade.id}})
      .then((response) => {
        if(response.data.success) {
          this.$emit('delete', unidade)
          this.toast.fire({
            icon: 'success',
            title: `Unidade Excluida com sucesso!`
          })
          return 
        }

        this.toast.fire({
          icon: 'error',
          title: `Ocorreu um erro ao Excluir a Unidade!`
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