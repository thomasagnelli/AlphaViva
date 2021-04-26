<template>
<Modal :id="modalId" :title="`${lote.name}`" data-keyboard="false" data-backdrop="static" >
  <template slot="body">
    <div class="car">
      <div class="p-2">
        <ul class="nav nav-pills">
          <li v-if="reserva && reserva.status === 1" class="nav-item cursor-pointer m-1">
            <a class="nav-link active bg-success">Reservado</a>
          </li>

          <li v-if="reserva && reserva.status === 2" class="nav-item cursor-pointer">
            <a class="nav-link active bg-danger">Fechado</a>
          </li>
          
          <li v-if="!reserva" class="nav-item cursor-pointer">
            <a class="nav-link" :class="{'active': showForm === false}" @click.prevent="closeFormNewCliente()" >Buscar Cliente</a>
          </li>

          <li v-if="!reserva" class="nav-item cursor-pointer">
            <a class="nav-link" :class="{'active': showForm === true}" @click.prevent="showFormNewCliente()" type="button">Novo Cliente</a>
          </li>
        </ul>
      </div><!-- /.card-header -->
      <div class="p-3">

        <div v-if="reserva && reserva.status >= 1" class="row">
          
          <div class="post col-12">
             <div v-if="reserva" class="user-block">
               <img class="img-circle img-bordered-lg" src="/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
               <span class="username">
                 <h4 class="text-secondary" v-if="userReserva" href="#">{{userReserva.name}}</h4>
               </span>
               <span class="description">{{reserva.cliente.cpf ? reserva.cliente.cpf : reserva.cliente.cnpj}}</span>
             </div>

             <div class="row mt-3">
              <a v-if="reserva && reserva.status === 1" data-dismiss="modal" @click.prevent="removeReserva()" class="btn bg-secondary m-1 float-right">
                <i class="fas fa-ban"></i> Remover
              </a>
               
              <a v-if="reserva && reserva.status === 1" data-dismiss="modal"  @click.prevent="setAsClosed()" class="btn bg-danger m-1 float-right">
                  <i class="fas fa-window-close"></i> Fechar
              </a>
             </div>
          </div>
          

          <div class="card card-widget widget-user col-12">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-light">
                <h3 v-if="userReserva" class="widget-user-username">{{userReserva.name}}</h3>
                <h5 class="widget-user-desc">{{reserva.lote.name}}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle elevation-2" src="/admin-lte/dist/img/user1-128x128.jpg" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="description-block">
                      <p class="description-header">Modificado</p>
                      <span class="description-text">{{moment(reserva.lote.updated_at).format('DD/MM/YYYY HH:mm:ss', 'pt-br')}}</span>
                    </div>
                  </div>
                 
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <a v-if="reserva && reserva.status === 1" data-dismiss="modal" @click.prevent="removeReserva()" class="btn bg-secondary mt-1">
                        <i class="fas fa-ban"></i> Remover
                      </a>
                    </div>
                  </div>
                  
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <a v-if="reserva && reserva.status === 1" data-dismiss="modal" @click.prevent="setAsClosed()" class="btn bg-danger mt-1">
                       <i class="fas fa-window-close"></i> Fechar
                      </a>
                    </div>
                  </div>
                  
                </div>
                <!-- /.row -->
              </div>
            </div>
        </div>

        <div v-if="!reserva" class="tab-content">
          <div v-if="showForm === false" class="active tab-pane">
              <form @submit.prevent="searchCliente()" class="form-horizontal row">
                <div class="form-group input-group input-group-md mb-0">
                  <input @input="searchCliente()" v-model="search.term" class="form-control form-control-sm" placeholder="Buscar por Nome ou CPF/CNPJ">
                
                  <div class="input-group-append">
                    <button @click="searchCliente()" type="button" class="btn btn-default btn-sm"><i class="fas fa-search"></i></button>
                  </div>
                </div>
              </form>

              <div class="post mt-3">
                 <div v-if="selectedCliente" class="user-block active cursor-pointer">
                    <div class="left">
                      <img class="img-circle img-bordered-sm" src="/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
                      <span class="username">
                        <a>{{selectedCliente.user.name}}</a>
                      </span>
                       <span class="description">{{selectedCliente.cpf ? selectedCliente.cpf : selectedCliente.cnpj}}</span>
                    </div>
                    <a @click.prevent="selectCliente()" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                </div>

                <template v-for="(cliente, c) in search.results">
                  <div @click.prevent="selectCliente(cliente)" v-if="(selectedCliente && cliente.id !== selectedCliente.id) || !selectedCliente" :key="c" class="user-block cursor-pointer">
                    <div class="left">
                      <img class="img-circle img-bordered-sm" src="/admin-lte/dist/img/user1-128x128.jpg" alt="user image">
                      <span class="username">
                        <a>{{cliente.user.name}}</a>
                      </span>
                      <span class="description">{{cliente.cpf ? cliente.cpf : cliente.cnpj}}</span>
                    </div>
                  </div>
                </template>
              </div>
          </div>

          <div  v-if="showForm === true" class="active tab-pane">
             <Form :mode="'create'" @submitForm="handleSubmitFormNewCliente($event)"/>
          </div>
        </div>

      </div><!-- /.card-body -->
    </div>
  </template>
  <template slot="action">
    <button v-if="!reserva" :disabled="!selectedCliente || !selectedCliente.userId" @click.prevent="createReserva()" data-dismiss="modal" type="button" class="btn btn-primary">Reservar</button>
  </template>
</Modal>           
</template>
<script>
import Modal from './../Modal'
import Form from './../clientes/clientes-form'
import Swal from 'sweetalert2'
import moment from 'moment';

export default {
  name: 'lote',
  components: {
    Modal,
    Form
  },
  props: {
    modalId: String,
    lote: Object,
    clientes: Array,
    reserva: Object,
  },
  data(){
    return{
      moment: moment,
       toast: Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      }),
      search: {
        term: "",
        results: [],
      },
      userReserva: null,
      selectedCliente: null,
      showForm: false,
    }
  },
  methods: {
    handleSubmitFormNewCliente(cliente = null){
      this.selectedCliente(cliente)
      this.$emit('getClientes')
    },
    selectCliente(cliente = null){
      this.selectedCliente = cliente
      this.closeFormNewCliente()
    },
    getUserReserva(){
      if(this.reserva){
        axios.get(`/usuario/${this.reserva.cliente.userId}`)
        .then((response) => {
          this.userReserva = response.data
        })
        .catch(() => {
          this.userReserva = null
        })
      }
    },
    createReserva(){
      if(this.selectedCliente) {
        if(this.showForm) {
          this.closeFormNewCliente()
        }
        
        axios.post(
          '/reserva',
          {
            clienteId: this.selectedCliente.id,
            loteId: this.lote.id,
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.toast.fire({
              icon: 'success',
              title: `Reserva criada com sucesso!`
            })

            this.$emit('getClientes')
            this.$emit('getReservas')
            //this.$emit('update')
            return
          }
          
          this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar criar reserva!`
          })
          this.$emit('update')
        })
        .catch((error) => {
          if (error && error.response.error){
            this.toast.fire({
              icon: 'error',
              title: `${error.response.error}`
            })
            this.selectedCliente = null
            return
          }

          this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar criar reserva!`
          })
          
          this.selectedCliente = null
          window.$(`#${this.modalId}`).modal('hide')
        })
      }
    },
    removeReserva(){
      axios.delete(
          '/reserva',
          {
            params: {
              loteId: this.lote.id,
            }
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.toast.fire({
              icon: 'success',
              title: `Reserva exluída com sucesso!`
            })

            this.$emit('getClientes')
            this.$emit('getReservas')
            //.$emit('update')
            return
          }

          this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar excluir reserva!`
          })
        })
        .catch((error) => {
          if (error && error.response.error){
            this.toast.fire({
              icon: 'error',
              title: `${error.response.error}`
            })
            return
          }

           this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar excluir reserva!`
          })
        })
    },
    setAsClosed(){
      axios.patch('/reserva', {loteId: this.lote.id})
      .then((response) => {
          if (response.data.success) {
            this.toast.fire({
              icon: 'success',
              title: `Reserva atualizada com sucesso!`
            })

            this.$emit('getClientes')
            this.$emit('getReservas')
            //this.$emit('update')
            return
          }

          this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar atualizar reserva!`
          })
        })
        .catch((error) => {
          if (error && error.response.error){
            this.toast.fire({
              icon: 'error',
              title: `${error.response.error}`
            })
            return
          }

          this.toast.fire({
            icon: 'error',
            title: `Ocoreu um erro ao tentar atualizar reserva!`
          })
        })
    },
    searchCliente(){
      if (this.search.term && this.search.term.length >= 1) {
        this.search.results = this.clientes.filter((cliente) => {
            return cliente.user.name.toLowerCase().includes(this.search.term.toLowerCase()) |
            ((cliente.cpf && cliente.cpf.includes(this.search.term)) | (cliente.cnpj && cliente.cnpj.includes(this.search.term)))
        })
        return 
      }
      this.search.results = []
    },
    showFormNewCliente(){
      this.selectedCliente = null
      this.search.results = []
      this.showForm = true
    },
    closeFormNewCliente(){
      this.showForm = false
      this.searchCliente()
    }
  },
  beforeMount(){
    this.getUserReserva()
  },
  watch:{
    reserva(){
      setTimeout(() => {this.getUserReserva()}, 10)
    }
  }
}
</script>