<template>
<div class="card card-custom col-12 my-3">
  <div class="card-header">
    <h3 class="card-title">Clientes</h3>
   
    <div class="card-tools flex">
      <a :data-target="'#modalNewCliente'" data-toggle="modal" href="#" class="badge cursor-pointer bg-success p-2 mr-2"><i class="fas fa-plus"></i></a>
      <Pagination 
        v-show="showForm === false" 
        @updateClientes="updateClientes($event)" 
        :baseUrl="'/clientes/paginate'"
      />
    </div>
  </div>

  <div  v-show="loading === false && clientes.length > 0" class="card-body heigth70">
    <Table
      :clientes="clientes" 
      @update="$emit('update', $event)" 
      @delete="$emit('delete', $event)" 
    />
  </div>

  <div  v-show="loading === false && clientes.length === 0" class="card-body flex flex-column heigth70">
    <p class="text-center text-secondary">Nenhum Cliente criado no momento</p>
    <a :data-target="'#modalNewCliente'" data-toggle="modal" href="#" class="btn btn-primary btn-lg p-2"><i class="fas fa-plus"></i>&nbsp; Novo Cliente</a>
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
    :modalId="'modalNewCliente'"
    @update="$emit('update')"
  />

</div>
</template>
<script>
import Pagination from './clientes-pagination'
import Table from './clientes-table'
import Form from './clientes-form-modal'

export default {
  name: 'clientes',
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
      clientes: [],
      lote: null,
      showForm: false,
      formMode: null,
    }
  },
  methods: {
    updateClientes(clientes){
      if(this.clientes.length === 0 ){
        this.loading = true
      }
      
      this.clientes = clientes
      this.$parent.$emit('update')

       if(this.clientes.length === 0 || this.loading === true){
         setTimeout(() => {this.loading = false}, 1000)
       }
    }
  }
}
</script>