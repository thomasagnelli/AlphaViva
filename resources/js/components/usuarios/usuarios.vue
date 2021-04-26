<template>
<div class="card card-custom col-12 my-3">
  <div class="card-header">
    <h3 class="card-title">Usuários</h3>
    <div class="card-tools flex">
      <a :data-target="'#modalNewUsuario'" data-toggle="modal" href="#" class="badge cursor-pointer bg-success p-2 mr-2"><i class="fas fa-plus"></i></a>
      <Pagination 
        v-show="showForm === false" 
        @updateUsuarios="updateUsuarios($event)" 
        :baseUrl="'/usuarios/paginate'"
      />
    </div>
  </div>

  <div  v-show="loading === false && usuarios.length > 0" class="card-body heigth70">
    <Table 
      :usuarios="usuarios"
      :Auth="Auth"
      @update="$emit('update', $event)" 
      @delete="$emit('delete', $event)" 
    />
  </div>

  <div  v-show="loading === false && usuarios.length === 0" class="card-body flex flex-column heigth70">
    <p class="text-center text-secondary">Nenhum Usuário no momento</p>
    <a :data-target="'#modalNewUsuario'" data-toggle="modal" href="#" class="btn btn-primary btn-lg p-2"><i class="fas fa-plus"></i>&nbsp; Novo Usuário</a>
  </div>

  <div v-show="loading === true" class="card-body p-3 flex heigth70">
   <div  class="text-center">
     <div class="spinner-border" role="status">
       <span class="sr-only">Loading...</span>
     </div>
   </div>
  </div>

  <Form 
    :mode="'create'"
    :modalId="'modalNewUsuario'"
    @update="$emit('update')"
  />

</div>
</template>
<script>
import Pagination from './usuarios-pagination'
import Table from './usuarios-table'
import Form from './usuarios-form'

export default {
  name: 'usuarios',
  props:{
    Auth: Object,
  },
  components:{
    Pagination,
    Table,
    Form
  },
  data(){
    return {
      loading: true,
      usuarios: [],
      lote: null,
      showForm: false,
      formMode: null,
    }
  },
  methods: {
    updateUsuarios(usuarios){
      if(this.usuarios.length === 0 ){
        this.loading = true
      }
      
      this.usuarios = usuarios
      this.$parent.$emit('update')

       if(this.usuarios.length === 0 || this.loading === true){
         setTimeout(() => {this.loading = false}, 1000)
       }
    }
  }
}
</script>