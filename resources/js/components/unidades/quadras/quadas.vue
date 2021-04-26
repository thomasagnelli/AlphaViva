<template>
<div class="card card-custom col-12 my-3">
  <div class="card-header">
    <h3 v-if="quadra" class="card-title">{{quadra.name}}</h3>
    <h3 v-else class="card-title">Quadras</h3>
   
    <div class="card-tools">
      <a v-show="showLotes === false" :data-target="'#modalNewQuadra'" data-toggle="modal" href="" class="badge cursor-pointer bg-success p-2 mr-2"><i class="fas fa-plus"></i> <b>&nbsp;Nova Quadra</b></a>
      <a v-show="showLotes === true" @click.prevent="closeLotesQuadra()" class="badge cursor-pointer p-2"><i class="fas fa-times"></i> <b>&nbsp;Fechar</b></a>
      
      <Pagination 
        v-show="showLotes === false" 
        @updateQuadras="updateQuadras($event)" 
        :unidadeId="unidade.id" 
        :baseUrl="'/quadras/paginate'"
      />
    </div>
  </div>

  <div v-show="showLotes === false && loading === false && quadras.length > 0" class="card-body heigth30">
    <Table 
      :unidadeId="unidade.id" 
      :quadras="quadras"
      @showLotesQuadra="showLotesQuadra($event)"
      @update="$emit('update', $event)" 
      @delete="$emit('delete', $event)" 
     />
  </div>

  <div  v-show="showLotes === false && loading === false && quadras.length === 0" class="card-body flex flex-column heigth30">
    <p class="text-center text-secondary">Nenhuma Quadra criada no momento</p>
    <a :data-target="`#modalNewQuadra`" data-toggle="modal" href="#" class="btn btn-primary btn-lg p-2"><i class="fas fa-plus"></i>&nbsp; Nova Quadra</a>
  </div>

  <div v-show="loading === true && showLotes === false" class="card-body p-3 flex heigth30">
   <div  class="text-center">
     <div class="spinner-border" role="status">
       <span class="sr-only">Loading...</span>
     </div>
   </div>
  </div>

  <Form 
    :unidadeId="unidade.id"
    :mode="'create'"
    :modalId="'modalNewQuadra'"
    @update="$emit('update')"
  />

  <Lotes 
    v-if="showLotes && quadra"
    :quadraId="quadra.id"
    @update="$emit('update')"
  />

</div>
</template>
<script>
import Pagination from './quadras-pagination'
import Table from './quadras-table'
import Form from './quadras-form'
import Lotes from './lotes/lotes'

export default {
  name: 'quadras',
  props: {
    unidade: Object,
  },
  components:{
    Pagination,
    Table,
    Form,
    Lotes
  },
  data(){
    return {
      loading: true,
      quadras: [],
      quadra: null,
      showLotes: false,
      showForm: false,
      showTable: false,
      formMode: null,
    }
  },
  methods: {
    showLotesQuadra(quadra){
      this.quadra = quadra
      this.showLotes = true
      this.$emit('showLotesQuadra', this.quadra)
    },
    closeLotesQuadra(emit=true){
      this.showLotes = false
      this.quadra = null
      if(emit){
        this.$emit('closeLotesQuadra')
      }
    },
    updateQuadras(quadras){
      if(this.quadras.length === 0){
        this.loading = true
      }
      
      this.quadras = quadras
      this.$parent.$emit('update')

      if(this.quadras.length === 0 || this.loading === true){
        setTimeout(() => {this.loading = false}, 1000)
      }
    }
  },
  mounted(){
    this.$parent.$on('closeLotesQuadra',() => {this.closeLotesQuadra(false)})
  }
}
</script>