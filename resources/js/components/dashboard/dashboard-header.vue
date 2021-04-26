<template>
  <div class="row">
    <div v-for="(box, bk) in boxes" :key="bk" class="col-md-3 col-sm-6 col-12">
      <div class="info-box">
        <span class="info-box-icon" :class="box.class"><i :class="box.icon"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{box.name}}</span>
          <span class="info-box-number">{{box.count}}</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios'

export default {
  name: 'dashboard-header',
  data() {
    return {
      boxes: {
        unidades: {
          name: 'Unidades',
          class: "bg-secondary",
          icon: "fas fa-map-signs",
          count: 0,
        },
        disponiveis: {
          name: 'Disponível',
          class: "bg-primary",
          icon: "far fa-calendar",
          count: 0,
        },
        reservadas: {
          name: 'Reservadas',
          class: "bg-success",
          icon: "far fa-calendar-check",
          count: 0,
        },
        fechadas: {
          name: 'Fechadas',
          class: "bg-danger",
          icon: "far fa-calendar-alt",
          count: 0,
        },
      }
    }
  },
  methods:{
    getEstatisticas(){
      axios.get('/reservas/estatisticas')
      .then((response) => {
        let keys = Object.keys(response.data)
        keys.forEach((key) => {
          this.boxes[key].count = response.data[key]
        }) 
      })
    }
  },
  mounted(){
    this.getEstatisticas()

    this.$parent.$on('getEstatisticas', () => {
      this.getEstatisticas()
    })
  }
}
</script>