<template>
<div class="container p-3">
  <Header/>
  <Unidades 
    @getClientes="getClientes()"
    @getReservas="getReservas()"
    :clientes="clientes"
    :reservas="reservas" 
  />
</div>
</template>
<script>
import Header from './dashboard-header'
import Unidades from './unidades'
export default {
  name: 'dashboard',
  props:{
    Auth: Object,
  },
  components: {
    Header,
    Unidades
  },
  data(){
    return{
      clientes: [],
      reservas: [],
    }
  },
  methods: {
    getClientes(){
      axios.get('/clientes/nopaginate')
      .then((response) => {this.clientes = response.data})
      .catch(() => {this.clientes = []})
    },
    getReservas(){
      axios.get('/reservas/nopaginate')
      .then((response) => {this.reservas = response.data})
      .catch(() => {this.reservas = []})
      this.$emit('getEstatisticas')
    }
  },
  beforeMount() {
    this.getClientes()
    this.getReservas()
  },
}
</script>