<template>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Descrição</th>
      <th>Status</th>
      <th style="width: 60px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <TableItem 
      @update="$emit('update', $event)"
      @delete="$emit('delete', $event)"
      v-for="lote in lotes"
      :key="lote.id"
      :lote="lote"
      :quadraId="quadraId"
      :reserva="getReservaByLote(lote.id)"
    />
  </tbody>
</table>
</template>
<script>
import TableItem from './lotes-table-item'

export default {
  name: 'lotes-table',
  props: {
    quadraId: Number,
    lotes: Array,
    reservas: Array,
  },
  components:{
    TableItem
  },
  methods: {
    getReservaByLote(loteId) {
      return this.reservas.find(res => res.loteId == loteId)
    }
  }
}
</script>