@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Sampling Kondisi Mobil - {{ $identitas->nama_bahan }}</h4>

    <div id="alert-area"></div>

    <form id="form-kondisi-mobil">
        @csrf
        <input type="hidden" name="id_identitas" value="{{ $identitas->id }}">

        <div class="mb-3">
            <label class="form-label">a. Bersih</label><br>
            <label><input type="radio" name="bersih" value="yes"> Iya</label>
            <label><input type="radio" name="bersih" value="no"> Tidak</label>
        </div>

        <div class="mb-3">
            <label class="form-label">b. Kering</label><br>
            <label><input type="radio" name="kering" value="yes"> Iya</label>
            <label><input type="radio" name="kering" value="no"> Tidak</label>
        </div>

        <div class="mb-3">
            <label class="form-label">c. Tidak Ada Benda Asing</label><br>
            <label><input type="radio" name="benda_asing" value="yes"> Iya</label>
            <label><input type="radio" name="benda_asing" value="no"> Tidak</label>
        </div>

        <div class="mb-3">
            <label class="form-label">d. Tidak Cacat / Bolong</label><br>
            <label><input type="radio" name="cacat" value="yes"> Iya</label>
            <label><input type="radio" name="cacat" value="no"> Tidak</label>
        </div>

        <div class="mb-3">
            <label class="form-label">e. Segel</label><br>
            <label><input type="radio" name="segel" value="yes"> Iya</label>
            <label><input type="radio" name="segel" value="no"> Tidak</label>
        </div>

        <div class="mb-4">
            <label class="form-label">f. Tidak Berbau</label><br>
            <label><input type="radio" name="berbau" value="yes"> Iya</label>
            <label><input type="radio" name="berbau" value="no"> Tidak</label>
        </div>

        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Sampling</button>
    </form>
</div>

<script>
    $('#form-kondisi-mobil').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: "{{ route('sampling.kondisi_mobil.store') }}",
            method: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
                form.trigger('reset');
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let list = '';
                    $.each(errors, function(key, value) {
                        list += `<li>${value[0]}</li>`;
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        html: `<ul style="text-align:left;">${list}</ul>`
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menyimpan data.'
                    });
                }
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Simpan Sampling');
            }
        });
    });
</script>
@endsection