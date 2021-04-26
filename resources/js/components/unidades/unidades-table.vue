<template>
<table class="table table-striped">
  <thead>
    <tr>
      <th>Nome</th>
      <th>Endereço</th>
      <th>Descrição</th>
      <th>Quadras</th>
      <th style="width: 60px">Ações</th>
    </tr>
  </thead>
  <tbody>
    <TableItem 
      @showQuadrasUnidade="$emit('showQuadrasUnidade', $event)" 
      @update="$emit('update', $event)" 
      @delete="$emit('delete', $event)" 
      v-for="unidade in unidades" 
      :key="unidade.id" 
      :unidade="unidade"
    />
  </tbody>
</table>
</template>
<script>
import TableItem from './unidades-table-item'

export default {
  name: 'unidades-table',
  props: {
    unidades: Array
  },
  components:{
    TableItem
  },
}
</script>