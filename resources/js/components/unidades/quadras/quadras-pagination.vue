<template>
 <ul class="pagination pagination-sm float-right">
    <li v-if="page > 1" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getQuadras(`${baseUrl}?&page=1`)">«</a></li>
    <li v-if="page >= 2" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getQuadras(`${baseUrl}?&page=${page - 1}`)">{{page - 1}}</a></li>
    <li class="page-item cursor-pointer active"><a class="page-link" @click.prevent="getQuadras(`${baseUrl}?&page=${page}`)">{{page}}</a></li>
    <li v-show="lastPage > page" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getQuadras(`${baseUrl}?&page=${page + 1}`)">{{page + 1}}</a></li>
    <li v-if="nextPageUrl" class="page-item cursor-pointer"><a class="page-link" @click.prevent="getQuadras(nextPageUrl)">»</a></li>
</ul>
</template>
<script>
import axios from 'axios'

export default {
  name: 'quadras-pagination',
  props:{
    baseUrl: String,
    unidadeId: Number,
    perPage: {
      type: Number,
      default: 10
    },
    noUpdate: {
      type: Boolean,
      default: false
    },
  },
  data(){
    return {
      quadras: [],
      page: 1,
      lastPage: 1,
      firstPageUrl: `${this.baseUrl}?page=${this.page}`,
      nextPageUrl: `${this.baseUrl}?page=${this.page + 1}`,
    }
  },
  methods: {
    getQuadras(url=null){
      if (!url){
        url = `${this.baseUrl}?page=${this.page}`
      }
      url = url + `&unidadeId=${this.unidadeId}&perPage=${this.perPage}`

      axios.get(url)
      .then((response) => {
        this.page = response.data.current_page
        this.lastPage = response.data.last_page
        this.firstPageUrl = response.data.first_page_url
        this.prevPageUrl = response.data.prev_page_url
        this.nextPageUrl = response.data.next_page_url
        this.hasMorePages = this.lastPage > this.page
        this.quadras = response.data.data
        this.$emit('updateQuadras', this.quadras)
      })
      .catch(() => {
        this.page = 1
        this.lastPage = 1
        this.hasMorePages = false
        this.firstPageUrl = `${this.baseUrl}?unidadeId=${this.unidadeId}&page=${this.page}`
        this.nextPageUrl = `${this.baseUrl}?unidadeId=${this.unidadeId}&page=${this.page + 1}`
        this.$emit('updateQuadras', [])
      })
    }
  },
  beforeMount(){
    if (this.quadras.length === 0) {
      this.getQuadras()
    }
    
    if(localStorage.page){
      this.page = JSON.parse(localStorage.page)
    }
  },
  mounted(){
    this.$parent.$on('delete', () => {
      if (this.noUpdate === false) {
        this.getQuadras()
      }
    })

    this.$parent.$on('create', () => {
      if (this.noUpdate === false) {
        this.getQuadras()
      }
    })

    this.$parent.$on('update', () => {
      if (this.noUpdate === false) {
        this.getQuadras()
      }
    })
  },
  watch: {
    page(){
      localStorage.page = this.page
    }
  }
}
</script>