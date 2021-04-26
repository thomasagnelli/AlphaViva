<template>
<div class="card card-custom col-12 my-3">
  <div class="card-header">
    <h3 class="card-title">Lotes</h3>
   
    <div class="card-tools flex">
      <a :data-target="'#modalNewLote'" data-toggle="modal" href="#" class="badge cursor-pointer bg-success p-2 mr-2"><i class="fas fa-plus"></i></a>
      <Pagination 
        v-show="showForm === false" 
        @updateLotes="updateLotes($event)"
        @updateReservas="updateReservas($event)"
        :quadraId="quadraId" 
        :baseUrl="'/lotes/paginate'"
      />
    </div>
  </div>

  <div  v-show="loading === false && lotes.length > 0" class="card-body heigth70">
    <Table 
      :quadraId="quadraId" 
      :lotes="lotes"
      :reservas="reservas"
      @update="$emit('update', $event)" 
      @delete="$emit('delete', $event)" 
    />
  </div>

  <div  v-show="loading === false && lotes.length === 0" class="card-body flex flex-column heigth70">
    <p class="text-center text-secondary">Nenhum Lote criado no momento</p>
    <a :data-target="'#modalNewLote'" data-toggle="modal" href="#" class="btn btn-primary btn-lg p-2"><i class="fas fa-plus"></i>&nbsp; Novo Lote</a>
  </div>

  <div v-show="loading === true" class="card-body p-3 flex heigth70">
   <div  class="text-center">
     <div class="spinner-border" role="status">
       <span class="sr-only">Loading...</span>
     </div>
   </div>
  </div>

  <Form 
    :quadraId="quadraId"
    :mode="'create'"
    :modalId="'modalNewLote'"
    @update="$emit('update')"
  />

</div>
</template>
<script>
import Pagination from './lotes-pagination'
import Table from './lotes-table'
import Form from './lotes-form'

export default {
  name: 'lotes',
  props: {
    quadraId: Number
  },
  components:{
    Pagination,
    Table,
    Form
  },
  data(){
    return {
      loading: true,
      lotes: [],
      reservas: [],
      lote: null,
      showForm: false,
      formMode: null,
    }
  },
  methods: {
    updateLotes(lotes){
      if(this.lotes.length === 0 ){
        this.loading = true
      }

      this.lotes = lotes
       if(this.lotes.length === 0 || this.loading === true){
         setTimeout(() => {this.loading = false}, 1000)
      }
    },
    updateReservas(reservas){
       if(this.reservas.length === 0 ){
        this.loading = true
      }
      
      this.reservas = reservas
       if(this.reservas.length === 0 || this.loading === true){
         setTimeout(() => {this.loading = false}, 1000)
       }
    }
  }
}
</script>