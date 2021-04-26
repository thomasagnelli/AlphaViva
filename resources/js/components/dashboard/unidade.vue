<template>
<div class="container">
    <div class="row flex justify-content-between align-items-center">
      <ul class="nav nav-tabs" id="custom-content-above-tab" role="tablist">
         <li v-for="(tab, statusKey) in tabs" :key="statusKey" class="nav-item" >
          <a 
            :class="`nav-link cursor-pointer ${tab.class} ${tabActive === parseInt(statusKey)  ? 'active' : ''}`"
            id="custom-content-above-home-tab" 
            data-toggle="pill" 
            :href="`#tab${statusKey}`" 
            role="tab" 
            :aria-controls="`tabAria${statusKey}`" 
            aria-selected="true"
            @click.prevent="filterQuadras(statusKey)"
          >
            {{tab.name}}
          </a>
        </li>
      </ul>

       <Pagination 
        @updateQuadras="updateQuadras($event)" 
        :baseUrl="'/quadras/paginate'"
        :perPage="4"
        :unidadeId="unidade.id"
        :noUpdate="true"
       />
    </div>


    <div v-if="filtedQuadras.length > 0" class="card-quadra-content  height70 row">
      <Quadra
        v-for="quadra in filtedQuadras" :key="quadra.id" 
        :quadra="quadra" :clientes="clientes" :reservas="reservas"
        @getClientes="$emit('getClientes')" @getReservas="$emit('getReservas')"
      />
    </div>

    <div v-if="filtedQuadras.length === 0" class="row">
      <div class="col-12 flex heigth70">
         <p class="text-secondary"> <i class="fas fa-filter"></i> Nenhum resultado encontrado</p>
      </div>
    </div>
</div>
</template>
<script>
import Pagination from './../unidades/quadras/quadras-pagination'
import Quadra from './quadra'

export default {
  name: 'unidade',
  components:{
    Pagination,
    Quadra
  },
  props: {
    unidade: Object,
    clientes: Array,
    reservas: Array,
  },  
  data(){
    return{
      tabs: {
        0: {
          class: "bg-secondary",
          name: "Todos",
        },
        1: {
          class: "bg-primary",
          name: "Disponível",
        },
        2: {
          class: "bg-success",
          name: "Reservados",
        },
        3: {
          class: "bg-danger",
          name: "Fechados",
        },
      },
      tabActive: 0,
      quadras: [],
      filtedQuadras: [],
    }
  },
  methods:{
    getReservaByLote(loteId) {
      return this.reservas.find(res => res.loteId == loteId)
    },
    updateQuadras(quadras){
      this.quadras = quadras
    },
    filterQuadras(status = 0){
      this.tabActive = parseInt(status)
      switch(this.tabActive){
        case 1:
          this.filtedQuadras = this.quadras.filter(quadra => quadra.lotes.some(lt => !this.getReservaByLote(lt.id)))
          break;
        case 2:
          this.filtedQuadras = this.quadras.filter(quadra => quadra.lotes.some(lt => this.getReservaByLote(lt.id) && this.getReservaByLote(lt.id).status === 1))
          break;
        case 3:
          this.filtedQuadras = this.quadras.filter(quadra => quadra.lotes.every(lt => this.getReservaByLote(lt.id) && this.getReservaByLote(lt.id).status === 2))
          break;
        default: 
          this.filtedQuadras = this.quadras
      }
    }
  },
  beforeMount(){
    if (localStorage.unidade && localStorage.tabActive) {
      this.tabActive = localStorage.tabActive
      localStorage.removeItem('tabActive')
      return
    }
    this.tabActive = 0
  },
  beforeDestroy(){
    this.$parent.$on('closeQuadrasUnidade', () => {
      localStorage.removeItem('tabActive')
    })
  },
  watch:{
    tabActive(){
      setTimeout(() => {
        localStorage.tabActive = this.tabActive
      }, 100)
    },
    quadras(){
      this.filterQuadras(this.tabActive)
    }
  }
}
</script>