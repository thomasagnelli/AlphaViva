<template>
<div class="content">
  <!-- Content Header (Page header) -->
  <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Unidades</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item" :class="{'active': !showQuadras}" >
                <a @click.prevent="closeQuadrasUnidade()" href="#">Unidades</a>
              </li>
              <li v-if="showQuadras" class="breadcrumb-item" :class="{'active': showQuadras}">
                <a @click.prevent="closeLotesQuadra()" href="#">{{unidade.name}}</a>
              </li>
              <li v-if="showQuadras && showLotes" class="breadcrumb-item" :class="{'active': showQuadras && showLotes}">
                {{quadra.name}}
              </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
  </section>

  <div class="card card-custom col-12">
    <div class="card-header">
      <h3 v-if="unidade" class="card-title">{{unidade.name}}</h3>
      <h3 v-else class="card-title">Unidades</h3>
    
      <div class="card-tools">
        <a 
          v-show="showQuadras === false" 
          :data-target="'#modalNewUnidade'" 
          data-toggle="modal" href="" 
          class="badge cursor-pointer bg-success p-2 mr-2"
        >
          <i class="fas fa-plus"></i> <b class="">&nbsp;Nova Unidade</b>
        </a>

        <a 
          v-show="showQuadras === true" 
          @click.prevent="closeQuadrasUnidade()" 
          class="badge cursor-pointer p-2"
        >
          <i class="fas fa-times"></i> <b class="">&nbsp;Fechar</b>
        </a>

        <Pagination
          v-show="showQuadras === false"
          @updateUnidades="updateUnidades($event)"
          :baseUrl="'/unidades/paginate'"
        />
      </div>
    </div>

    <div v-show="showQuadras === false && loading === false && unidades.length > 0" class="card-body heigth70">
      <Table 
        :unidades="unidades"
        @showQuadrasUnidade="showQuadrasUnidade($event)"
        @update="$emit('update', $event)"
        @delete="$emit('delete', $event)"
      />
    </div>

    <div  v-show="showQuadras === false && loading === false && unidades.length === 0" class="card-body flex flex-column heigth70">
      <p class="text-center text-secondary">Nenhuma Unidade criada no momento</p>
      <a :data-target="'#modalNewUnidade'" data-toggle="modal" href="" class="btn btn-primary btn-lg p-2"><i class="fas fa-plus"></i>&nbsp; Nova Unidade</a>
    </div>

    <div v-show="loading === true && showQuadras === false" class="card-body p-3 flex heigth70">
      <div  class="text-center">
        <div class="spinner-border" role="status">
          <span class="sr-only">Loading...</span>
        </div>
      </div>
    </div>
  </div>

  <Form 
    :modalId="'modalNewUnidade'"
    :mode="'create'"
    @update="$emit('update')"
  />

  <Quadras 
    class="col-12" 
    v-if="showQuadras && unidade" 
    :unidade="unidade" 
    @update="$emit('update')"
    @showLotesQuadra="showLotesQuadra($event)"
    @closeLotesQuadra="closeLotesQuadra()"
  />
</div>
</template>
<script>
import Pagination from './unidades-pagination'
import Table from './unidades-table'
import Form from './unidades-form'
import Quadras from './quadras/quadas'

export default {
  name: 'unidades',
  props:{
    Auth: Object,
  },
  components:{
    Pagination,
    Table,
    Form,
    Quadras
  },
  data(){
    return {
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
      this.loading = true
      this.unidade = unidade
      this.showQuadras = true
      localStorage.unidade = JSON.stringify(this.unidade)
      localStorage.showQuadras = JSON.stringify(this.showQuadras)
      setTimeout(() => {this.loading = false}, 1000)
    },
    closeQuadrasUnidade(){
      this.loading = true
      this.unidade = null
      this.showQuadras = false
      localStorage.removeItem('unidade')
      localStorage.removeItem('showQuadras')
      setTimeout(() => {this.loading = false}, 1000)
    },
    showLotesQuadra(quadra){
      this.quadra = quadra
      this.showLotes = true
    },
    closeLotesQuadra(){
      this.quadra = null
      this.showLotes = false
      this.$emit('closeLotesQuadra')
    },
    updateUnidades(unidades){
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