<template>
 <ul class="pagination pagination-sm float-right">
    <li v-if="page > 1" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getLotes(`${baseUrl}?page=1`)">«</a></li>
    <li v-if="page >= 2" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getLotes(`${baseUrl}?page=${page - 1}`)">{{page - 1}}</a></li>
    <li class="page-item cursor-pointer active"><a class="page-link" @click.prevent="getLotes(`${baseUrl}?page=${page}`)">{{page}}</a></li>
    <li v-show="lastPage > page" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getLotes(`${baseUrl}?page=${page + 1}`)">{{page + 1}}</a></li>
    <li v-if="nextPageUrl" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getLotes(nextPageUrl)">»</a></li>
</ul>
</template>
<script>
import axios from 'axios'

export default {
  name: 'lotes-pagination',
  props:{
    baseUrl: String,
    quadraId: Number,
  },
  data(){
    return {
      lotes: [],
      page: 1,
      lastPage: 1,
      firstPageUrl: `${this.baseUrl}?page=${this.page}`,
      nextPageUrl: `${this.baseUrl}?page=${this.page + 1}`,
    }
  },
  methods: {
    getLotes(url=null){
      if (!url){
        url = `${this.baseUrl}?page=${this.page}`
      }
      url = url + `&quadraId=${this.quadraId}&perPage=${10}`

      axios.get(url)
      .then((response) => {
        this.page = response.data.current_page
        this.lastPage = response.data.last_page
        this.firstPageUrl = response.data.first_page_url
        this.prevPageUrl = response.data.prev_page_url
        this.nextPageUrl = response.data.next_page_url
        this.hasMorePages = this.lastPage > this.page
        this.lotes = response.data.data
        this.$emit('updateLotes', this.lotes)
      })
      .catch(() => {
        this.page = 1
        this.lastPage = 1
        this.hasMorePages = false
        this.firstPageUrl = `${this.baseUrl}?page=${this.page}`
        this.nextPageUrl = `${this.baseUrl}?page=${this.page + 1}`
        this.$emit('updateLotes', [])
      })
    },
    getReservas(){
      axios.get('/reservas/nopaginate')
      .then((response) => {this.$emit('updateReservas',response.data)})
      .catch(() => {this.$emit('updateReservas', [])})
    }
  },
  beforeMount(){
    this.getLotes()
    this.getReservas()

    if(localStorage.page){
      this.page = JSON.parse(localStorage.page)
    }
  },
  mounted(){
    this.$parent.$on('delete', () => {
      this.getLotes()
      this.getReservas()
    })

    this.$parent.$on('create', () => {
      this.getLotes()
      this.getReservas()
    })

    this.$parent.$on('update', () => {
      this.getLotes()
      this.getReservas()
    })
  },
  watch: {
    page(){
      localStorage.page = this.page
    }
  }
}
</script>