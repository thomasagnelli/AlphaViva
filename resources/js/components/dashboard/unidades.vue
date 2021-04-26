<template>
<div class="card card-custom card-primary card-outline col-12">
  <div class="card-header flex justify-content-between">
    <h3 v-if="showQuadras === true" class="card-title">
      <i class="fab fa-buromobelexperte"></i> {{unidade.name}}
    </h3>

    <h3 v-else class="card-title">
      <i class="fab fa-buromobelexperte"></i> &nbsp;Reservas
    </h3>

    <div class="card-tools">
      <a
        v-show="showQuadras === true" 
        @click.prevent="closeQuadrasUnidade()" 
        class="badge cursor-pointer p-2"
      >
        <i class="fas fa-times"></i>
      </a>

      <Pagination 
        v-show="showQuadras === false"
        @updateUnidades="updateUnidades($event)"
        :baseUrl="'/unidades/paginate'"
        :perPage="16"
      />
    </div>
  </div>

  <template v-if="showQuadras === false">
    <div v-show="loading === false && unidades.length > 0" class="card-body heigth70">
      <div class="row">
      <div 
        v-for="unidade in unidades" 
        :key="unidade.id"
        @click="showQuadrasUnidade(unidade)"
        class="col-md-3 col-sm-6 col-12"
        :class="{'cursor-pointer': unidade.active, 'cursor-not': !unidade.active}"
      >
        <div class="info-box">
          <span 
            class="info-box-icon"
            :class="{'bg-primary': unidade.active, 'bg-secondary': !unidade.active}"
          >
            <i class="fas fa-calendar-day"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">{{unidade.name}}</span>
          </div>
        </div>
      </div>
      </div>
    </div>

    <div v-if="loading === false && unidades.length === 0" class="card-body flex flex-column heigth70">
      <p class="text-center text-secondary">Nenhuma Unidade criada no momento</p>
    </div>
 </template>

 <template v-if="showQuadras === true">
    <div v-if="loading === false" class="card-body heigth70">
      <Unidade 
        :unidade="unidade" 
        :clientes="clientes" 
        :reservas="reservas"
        @getClientes="$emit('getClientes')"
        @getReservas="$emit('getReservas')"
      />
    </div>
 </template>

  <div v-if="loading === true" class="card-body p-3 flex heigth70">
    <div  class="text-center">
      <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import Pagination from './../unidades/unidades-pagination'
import Unidade from './unidade'

export default {
  name: 'unidades-dashboard',
  props:{
    clientes: Array,
    reservas: Array,
  },
  components:{
    Pagination,
    Unidade
  },
  data(){
    return{
      loading: true,
      unidades: [],
      unidade: null,
      quadra: null,
      showQuadras: false,
      showLotes: false,
      showLotes: false,
    }
  },
  methods: {
    showQuadrasUnidade(unidade){
      if(unidade.active){
        this.loading = true
        this.unidade = unidade
        this.showQuadras = true
        localStorage.unidade = JSON.stringify(this.unidade)
        localStorage.showQuadras = JSON.stringify(this.showQuadras)
        setTimeout(() => {this.loading = false}, 1000)
      }
    },
    closeQuadrasUnidade(){
      this.$emit('closeQuadrasUnidade')
      this.loading = true
      this.unidade = null
      this.showQuadras = false
      localStorage.removeItem('unidade')
      localStorage.removeItem('showQuadras')
      setTimeout(() => {this.loading = false}, 1000)
    },
    updateUnidades(unidades = []){
      if(this.unidades.length === 0){
        this.loading = true
      }
      
      this.unidades = unidades
      if(this.unidades.length === 0 || this.loading === true){
        setTimeout(() => {this.loading = false}, 1000)
      }
    }
  },
  beforeMount(){
    if(localStorage.unidade){
      this.unidade = JSON.parse(localStorage.unidade)
    }

    if(localStorage.showQuadras){
      this.showQuadras = JSON.parse(localStorage.showQuadras)
    }
  }
}
</script>