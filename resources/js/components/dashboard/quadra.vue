<template>
<div  class="shadow-sm card-quadra my-3">
  <div class="card-header">
    <h3 class="card-title">{{quadra.name}}</h3>

    <div class="card-tools">
      <div>
          <i class=""> </i>
      </div>
      <!-- Example single danger button -->
      <div>
        <!--
        <button type="button" class="btn dropdown-toggle" :id="`dropdown-menu-toggle${quadra.id}`" data-target="#dropdown-menu-quadra" data-bs-toggle="dropdown" aria-expanded="false">
          
        </button>
        <ul id="dropdown-menu-quadra" class="dropdown-menu">
          <li><a class="dropdown-item" href="#">Action</a></li>
          <li><a class="dropdown-item" href="#">Another action</a></li>
          <li><a class="dropdown-item" href="#">Something else here</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Separated link</a></li>
        </ul>
        -->
      </div>
    </div>
  </div>
  <div class="card-body">
    <div v-for="(group, lg) in formatedLotes" :key="lg" class="row">
      <div
        v-for="(lote, l) in group" :key="l" class="btn-app"
        :class="{
          'opened': !getReservaByLote(lote.id), 
          'reserved': getReservaByLote(lote.id) && getReservaByLote(lote.id).status == 1,
          'closed': getReservaByLote(lote.id) && getReservaByLote(lote.id).status == 2
        }"
        :data-target="`#modalLote${lote.id}`" data-toggle="modal"
      >
        {{lote.name}}
      </div>
      <Lote 
        v-for="(lote, l) in group" :key="(l+50)" 
        :lote="lote" :modalId="`modalLote${lote.id}`"
        :clientes="clientes"
        :reserva="getReservaByLote(lote.id)"
        @getClientes="$emit('getClientes')"
        @getReservas="$emit('getReservas')"
        @update="getLotes()"
      />
    </div>       
  </div>
</div>
</template>
<script>
import Lote from './lote'

export default {
  name: 'quadra',
  components: {
    Lote
  },
  props: {
    quadra: Object,
    clientes: Array,
    reservas: Array,
  },
  data(){
    return {
      loading: false,
      lotes: [],
      formatedLotes: [],
    }
  },
  methods: {
    getFormatedLotes(cut = 5){
      let lotes = []
      let j = 0
      for(let i = 0; i < this.lotes.length; i += cut) {
        lotes[j] = this.lotes.slice(i, (i + cut))
        j++
      }
      return lotes
    },
    getLotes(){
      this.loading = true
      axios.get(`/lotes/nopaginate?quadraId=${this.quadra.id}`)
      .then((response) => {
        this.lotes = response.data
        this.loading = false
      })
      .catch(() => {
        this.lotes = []
        this.loading = false
      })
    },
    getReservaByLote(loteId) {
      return this.reservas.find(res => res.loteId == loteId)
    }
  },
  beforeMount(){
    this.getLotes()
  },
  watch:{
    lotes(){
      this.formatedLotes = this.getFormatedLotes(5)
    }
  }
}
</script>