<template>
 <ul class="pagination pagination-sm float-right">
    <li v-if="page > 1" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUnidades(`${baseUrl}?page=1`)">«</a></li>
    <li v-if="page >= 2" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUnidades(`${baseUrl}?page=${page - 1}`)">{{page - 1}}</a></li>
    <li class="page-item cursor-pointer active"><a class="page-link" @click.prevent="getUnidades(`${baseUrl}?page=${page}`)">{{page}}</a></li>
    <li v-show="lastPage > page" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUnidades(`${baseUrl}?page=${page + 1}`)">{{page + 1}}</a></li>
    <li v-if="nextPageUrl" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUnidades(nextPageUrl+``)">»</a></li>
</ul>
</template>
<script>
import axios from 'axios'

export default {
  name: 'unidades-pagination',
  props:{
    baseUrl: String,
    perPage: {
      type: Number,
      default: 10
    },
  },
  data(){
    return {
      unidades: [],
      page: 1,
      lastPage: 1,
      firstPageUrl: `${this.baseUrl}?page=${this.page}`,
      nextPageUrl: `${this.baseUrl}?page=${this.page + 1}`,
    }
  },
  methods: {
    getUnidades(url=null){
      if (!url){
        url = `${this.baseUrl}?page=${this.page}`
      }
      url = url + `&perPage=${this.perPage}`

      axios.get(url)
      .then((response) => {
        this.page = response.data.current_page
        this.lastPage = response.data.last_page
        this.firstPageUrl = response.data.first_page_url
        this.prevPageUrl = response.data.prev_page_url
        this.nextPageUrl = response.data.next_page_url
        this.hasMorePages = this.lastPage > this.page
        this.unidades = response.data.data
        this.$emit('updateUnidades', this.unidades)
      })
      .catch(() => {
        this.page = 1
        this.lastPage = 1
        this.hasMorePages = false
        this.firstPageUrl = `${this.baseUrl}?page=${this.page}`
        this.nextPageUrl = `${this.baseUrl}?page=${this.page + 1}`
         this.$emit('updateUnidades', [])
      })
    }
  },
  beforeMount(){
    if (this.unidades.length === 0) {
      this.getUnidades()
    }
    
    if(localStorage.page){
      this.page = JSON.parse(localStorage.page)
    }
  },
  mounted(){
    this.$parent.$on('delete', () => {
      this.getUnidades()
    })

    this.$parent.$on('create', () => {
      this.getUnidades()
    })

    this.$parent.$on('update', () => {
      this.getUnidades()
    })
  },
  watch: {
    page(){
      localStorage.page = this.page
    }
  }
}
</script>