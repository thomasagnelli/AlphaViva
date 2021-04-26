<form action="/users" method="{{$form_method}}">
    {{ csrf_field() }}

    <input type="text" name="id" value="{{ old('id') ?? $id ?? ''}}" hidden/>
    <input type="text" name="name" value="{{ old('name') ?? $name ??''}}" placeholder="Jhon Snow" />
    <input type="text" name="phone"  value="{{ old('phone') ?? $phone ?? ''}}" placeholder="+00 (00) 000000000" />
    <input type="text" name="email"  value="{{ old('email') ?? $email ?? ''}}" placeholder="JhonSnow@mail.ex" />
    <input type="password" name="password" placeholder="********" />
    <select name="role_id" value="{{ old('role_id') ?? ''}}" >
      <!-- temp -->
      <option value="">Selecione</option>
      <option value="1">Admin</option>
      <option value="2">Manager</option>
      <option value="3">Client</option>
      <option value="4">Partner</option>
      <!-- temp -->
    <select>

    <input type="submit" value="Salvar" />
</form>
<script></script>
<script>
  var myForm = document.getElementById('myForm');
  formData = new FormData(myForm);
</script>