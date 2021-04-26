<template>
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Nome</th>
      <th style="width: 60px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <TableItem 
      @update="$emit('update', $event)"
      @delete="$emit('delete', $event)"
      v-for="cliente in clientes"
      :key="cliente.id"
      :cliente="cliente"
    />
  </tbody>
</table>
</template>
<script>
import TableItem from './clientes-table-item'

export default {
  name: 'clientes-table',
  props: {
    clientes: Array
  },
  components:{
    TableItem
  },
}
</script>