<template>
 <ul class="pagination pagination-sm float-right">
    <li v-if="page > 1" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUsuarios(`${baseUrl}?&page=1`)">«</a></li>
    <li v-if="page >= 2" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUsuarios(`${baseUrl}?&page=${page - 1}`)">{{page - 1}}</a></li>
    <li class="page-item cursor-pointer active"><a class="page-link" @click.prevent="getUsuarios(`${baseUrl}?&page=${page}`)">{{page}}</a></li>
    <li v-show="lastPage > page" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUsuarios(`${baseUrl}?&page=${page + 1}`)">{{page + 1}}</a></li>
    <li v-if="nextPageUrl" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getUsuarios(nextPageUrl)">»</a></li>
</ul>
</template>
<script>
import axios from 'axios'

export default {
  name: 'usuarios-pagination',
  props:{
    baseUrl: String,
    quadraId: Number,
    perPage: {
      type: Number,
      default: 10
    }
  },
  data(){
    return {
      usuarios: [],
      page: 1,
      lastPage: 1,
      firstPageUrl: `${this.baseUrl}?page=${this.page}`,
      nextPageUrl: `${this.baseUrl}?page=${this.page + 1}`,
    }
  },
  methods: {
    getUsuarios(url=null){
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
        this.usuarios = response.data.data
        this.$emit('updateUsuarios', this.usuarios)
      })
      .catch(() => {
        this.page = 1
        this.lastPage = 1
        this.hasMorePages = false
        this.firstPageUrl = `${this.baseUrl}?page=${this.page}`
        this.nextPageUrl = `${this.baseUrl}?page=${this.page + 1}`
        this.$emit('updateUsuarios', [])
      })
    }
  },
  beforeMount(){
    if (this.usuarios.length === 0) {
      this.getUsuarios()
    }

    if(localStorage.page){
      this.page = localStorage.page
    }
  },
  mounted(){
    this.$parent.$on('delete', () => {
      this.getUsuarios()
    })

    this.$parent.$on('create', () => {
      this.getUsuarios()
    })

    this.$parent.$on('update', () => {
      this.getUsuarios()
    })
  },
  watch: {
    page(){
      localStorage.page = this.page
    }
  }
}
</script>