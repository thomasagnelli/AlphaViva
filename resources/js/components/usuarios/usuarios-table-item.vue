<template>
<tr>
  <td><a :data-target="`#modalShowUsuario${usuario.id}`" data-toggle="modal" href="#"><i class="far fa-user"></i></a></td>
  <td><a :data-target="`#modalShowUsuario${usuario.id}`" data-toggle="modal" href="#">{{usuario.name}}</a></td>
  <td>{{roles[usuario.roleId]}}</td>
  <td class="flex">
    <div v-if="Auth.id !== usuario.id" class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input cursor-pointer" v-model="usuario.active" :id="`switchActiveUsuario${usuario.id}`">
        <label  @click="activeUsuario(usuario)" class="custom-control-label cursor-pointer" :for="`switchActiveUsuario${usuario.id}`"></label>
      </div>
    <span :data-target="`#modalUpdateUsuario${usuario.id}`" data-toggle="modal" class="badge cursor-pointer bg-info p-1"><i class="fas fa-pen-alt"></i></span>
    <span v-if="(roles[usuario.roleId] !== 'Cliente') && (Auth.id !== usuario.id)" :data-target="`#modalDeleleteUsuario${usuario.id}`" data-toggle="modal" class="badge cursor-pointer bg-danger p-1 ml-2"><i class="fas fa-trash"></i></span>
  </td>

  <Form 
    :usuario="usuario"
    :mode="'show'"
    :modalId="`modalShowUsuario${usuario.id}`"
  />
  
  <Form 
    :usuario="usuario"
    :mode="'update'"
    :modalId="`modalUpdateUsuario${usuario.id}`"
    @update="$emit('update')"
  />

  <Modal :title="'Excluir Usuário'" :id="`modalDeleleteUsuario${usuario.id}`">
    <template slot="body">
      <p>Deseja realmente Excluir o Usuário {{usuario.name}}?</p>
    </template>
    <template slot="action">
      <button data-dismiss="modal" @click.prevent="deleteUsuario(usuario)" type="button" class="btn btn-danger">Excluir</button>
    </template>
  </Modal>
</tr>
</template>
<script>
import Modal from './../Modal'
import Form from './usuarios-form'
import Swal from 'sweetalert2'

export default {
  name: 'usuarios-table-item',
  props:{
    usuario: Object,
    Auth: Object,
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
      roles: {
        1: "Administrador",
        2: "Gerente",
        3: "Cliente",
        4: "Parceiro",
      }
    }
  },
  methods:{
    activeUsuario(usuario){
      setTimeout(() => {
        let inputSwitch = document.querySelector(`#switchActiveUsuario${usuario.id}`)
        let active = false
        if (inputSwitch.checked === true){
          active = true
        }

        axios.patch(`/usuario`, {...usuario, active: active})
        .then((response) => {
          if(response.data.active){
            usuario.active = true
            return
          }
          usuario.active = false
        })
      }, 5)
    },
    deleteUsuario(usuario){
      axios.delete(`/usuario`, {params: {id: usuario.id}})
      .then((response) => {
        if(response.data.success) {
          this.$emit('delete', usuario)
          this.toast.fire({
            icon: 'success',
            title: `Usuario Excluido com sucesso!`
          })
          return 
        }

        this.toast.fire({
          icon: 'error',
          title: `Ocorreu um erro ao Excluir o Usuario!`
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