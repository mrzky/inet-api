<?php

// extends class Model
class Api_model extends CI_Model {

  // response jika field ada yang kosong
  public function empty_response() {
    $response['status']  = 502;
    $response['error']   = true;
    $response['message'] = 'Field tidak boleh kosong';
    return $response;
  }

  public function create_account($nama, $username, $password, $role) {

    if ($nama OR $username OR $password OR $role) {
      if (!$this->is_username_exist($username)){

        $data = array(
          'nama'     => $nama,
          'username' => $username,
          'password' => md5($password),
          'role'     => $role
        );

        $this->db->query("SET sql_mode=''");
        $this->db->set('uid','UUID()',FALSE);
        $insert = $this->db->insert('admin', $data);

        if($insert){
          $response['status']=200;
          $response['error']=false;
          $response['message']='Berhasil!';
          return $response;
        }else{
          $response['status']=502;
          $response['error']=true;
          $response['message']='Gagal!';
          return $response;
        }
      } else {
        $response['status']=502;
        $response['error']=true;
        $response['message']='Username sudah terdaftar! Harap gunakan username  lain.';
        return $response;
      }
    } else {
      return $this->empty_response();      
    }
  }

  public function login($username, $password){

    if (empty($username) || empty($password)) {
      return $this->empty_response();
    } else {
      
      $data = array();

      $this->db->query("SET sql_mode=''");
      $cek = $this->db->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
      $result1 = $cek->result();

      if($result1[0] != null){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Login berhasil';
        $response['user']    = $result1[0];
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Username/Password Salah! Atau akun anda belum terdaftar!';
        return $response;
       }
    }

  }

  public function update_account($uid, $nama, $username, $username_baru) {

      if (empty($uid) || empty($nama) || empty($username) || empty($username_baru)) {
        return $this->empty_response();
      } else {

        $set = array(
          'nama'        => $nama,
          'username'    => $username_baru,
          'update_date' => date('Y-m-d H:i:s')
        );

        if ($username_baru != $username){
          if (!$this->is_username_exist($username_baru)){
            $set['username'] == $username;
          } else {
            $response['status']=502;
            $response['error']=true;
            $response['message']='Username sudah terdaftar! Harap gunakan username lain.';
            return $response;
          }
        }

        // var_dump($set);die;

        $this->db->query("SET sql_mode=''");
        $this->db->where(array('uid'=>$uid));
        $update = $this->db->update('admin', $set);
        if($update){
          $response['status'] = 200;
          $response['error']   = false;
          $response['message'] = 'Berhasil disimpan.';
          return $response;
        }else{
          $response['status']  = 502;
          $response['error']   = true;
          $response['message'] = 'Gagal disimpan.';
          return $response;
        }
      }
  }

  public function read_account($uid) {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM admin WHERE uid='$uid'");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['profil']     = $result1[0];
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function list_account() {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM admin WHERE role='0' ORDER BY insert_date DESC");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['akun']     = $result1;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function delete_account($uid) {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("DELETE FROM admin WHERE uid='$uid'");

    if($cek){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil dihapus!';
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function create_paket($nama_paket, $harga) {

    if ($nama_paket OR $harga) {

      $data = array(
        'nama_paket'  => $nama_paket,
        'harga'       => $harga,
      );

      $this->db->query("SET sql_mode=''");
      $insert = $this->db->insert('paket_inet', $data);

      if($insert){
        $response['status']=200;
        $response['error']=false;
        $response['message']='Berhasil!';
        return $response;
      }else{
        $response['status']=502;
        $response['error']=true;
        $response['message']='Gagal!';
        return $response;
      }
    } else {
      return $this->empty_response();      
    }
  }

  public function update_paket($id, $nama_paket, $harga) {

      if (empty($id) || empty($nama_paket) || empty($harga)) {
        return $this->empty_response();
      } else {

        $set = array(
          'nama_paket'  => $nama_paket,
          'harga'       => $harga
        );

        $this->db->query("SET sql_mode=''");
        $this->db->where(array('id'=>$id));
        $update = $this->db->update('paket_inet', $set);
        if($update){
          $response['status'] = 200;
          $response['error']   = false;
          $response['message'] = 'Berhasil disimpan.';
          return $response;
        }else{
          $response['status']  = 502;
          $response['error']   = true;
          $response['message'] = 'Gagal disimpan.';
          return $response;
        }
      }
  }

  public function read_paket($id) {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM paket_inet WHERE id='$id'");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['paket']     = $result1[0];
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function list_paket() {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM paket_inet");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['paket']     = $result1;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function delete_paket($id) {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("DELETE FROM paket_inet WHERE id='$id'");

    if($cek){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil dihapus!';
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function create_transaksi($kode_user, $jumlah_unit, $uid_kades, $total_bayar) {

    $last_kode = $this->get_last_kode_transaksi();
    $kode_transaksi = str_pad($last_kode+1, 12, "0", STR_PAD_LEFT);

    if ($kode_user OR $jumlah_unit OR $uid_kades OR $total_bayar) {

      $data = array(
        'kode_transaksi'    => $kode_transaksi,
        'kode_user'         => $kode_user,
        'jumlah_unit'       => $jumlah_unit,
        'tanggal_transaksi' => date('Y-m-d H:i:s'),
        'uid_kades'         => $uid_kades,
        'total_bayar'       => $total_bayar,
      );

      $this->db->query("SET sql_mode=''");
      $this->db->set('uid','UUID()',FALSE);
      $insert = $this->db->insert('transaksi', $data);

      if($insert){
        $response['status']=200;
        $response['error']=false;
        $response['message']='Berhasil!';
        return $response;
      }else{
        $response['status']=502;
        $response['error']=true;
        $response['message']='Gagal!';
        return $response;
      }
    } else {
      return $this->empty_response();      
    }
  }

  public function read_transaksi($uid) {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM transaksi WHERE uid='$uid'");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['paket']     = $result1[0];
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function list_transaksi() {

    $this->db->query("SET sql_mode=''");
    $cek = $this->db->query("SELECT * FROM transaksi");
    $result1 = $cek->result();
    // var_dump($result1);die;

    if($result1 != null){
      $response['status']     = 200;
      $response['error']      = false;
      $response['message']    = 'Data berhasil diambil!';
      $response['paket']     = $result1;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Tidak ada data!';
      return $response;
    }
  }

  public function ubah_foto($id, $tipe_user, $file_name) {

    $where = array();

    if ($tipe_user == 'siswa') {
      $where['id_siswa'] = $id;
    } else {
      $where['id_ortu'] = $id;
    }

    $set = array(
      'foto' => $file_name,
    );

    // var_dump($set);die;
    $this->db->query("SET sql_mode=''");
    $this->db->where($where);
    $update = $this->db->update($tipe_user, $set);
    if($update){
      $response['status'] = 200;
      $response['error']   = false;
      $response['message'] = 'Foto profil disimpan.';
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal disimpan.';
      return $response;
    }
  }

  public function ubah_profil($id, $tipe_user, $nama, $email, $email_lama, $kontak, $alamat, $provinsi, $kab_kota) {

      if (empty($email) || empty($email_lama) || empty($nama) || empty($kontak) || empty($provinsi) || empty($kab_kota) || empty($alamat)) {
        return $this->empty_response();
      } else {

        $where = array();

        if ($tipe_user == 'siswa') {
          $where['id_siswa'] = $id;
        } else if ($tipe_user == 'swasta') {
          $where['id_swasta'] = $id;
        } else if ($tipe_user == 'isntansi') {
          $where['id_instansi'] = $id;
        } else if ($tipe_user == 'ortu') {
          $where['id_ortu'] = $id;
        }

        $set = array(
          'nama'      => $nama, 
          // 'email'     => $email,
          'kontak'    => $kontak, 
          'alamat'    => $alamat, 
          'provinsi'  => $provinsi, 
          'kab_kota'  => $kab_kota
        );

        if ($email != $email_lama){
          if (!$this->is_email_exist($email)){
            $set['email'] == $email;
          } else {
            $response['status']=502;
            $response['error']=true;
            $response['message']='Email sudah terdaftar! Harap gunakan email lain.';
            return $response;
          }
        }

        // var_dump($set);die;

        $this->db->query("SET sql_mode=''");
        $this->db->where($where);
        $update = $this->db->update($tipe_user, $set);
        if($update){
          $response['status'] = 200;
          $response['error']   = false;
          $response['message'] = 'Profil disimpan.';
          return $response;
        }else{
          $response['status']  = 502;
          $response['error']   = true;
          $response['message'] = 'Gagal disimpan.';
          return $response;
        }
      }
  }

  public function ubah_password($id, $tipe_user, $passSekarang, $passBaru, $passBaruUlangi) {

    if (empty($passBaru) || empty($tipe_user) || empty($id) || empty($passSekarang) || empty($passBaruUlangi)) {
      return $this->empty_response();
    } else {

      $where = array();
      $pass = '';

      if ($tipe_user == 'siswa') {
        $where['id_siswa'] = $id;
        $this->db->query("SET sql_mode=''");
        $query = $this->db->query("SELECT password FROM $tipe_user WHERE id_siswa='$id'");
        $result = $query->result();
        $pass = $result[0]->password;

      } else {
        $where['id_ortu'] = $id;
        $this->db->query("SET sql_mode=''");
        $query = $this->db->query("SELECT password FROM $tipe_user WHERE id_ortu='$id'");
        $result = $query->result();
        $pass = $result[0]->password;
      }

      $set = array(
        'password'      => md5($passBaru), 
        'pass_asli'     => $passBaru,
      );

      // echo json_encode($passSekarang. ' ' . $pass);die;

      if ($pass != md5($passSekarang)) {
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Password sekarang salah!';
        return $response;
      } else {
      	$this->db->query("SET sql_mode=''");
        $this->db->where($where);
        $update = $this->db->update($tipe_user, $set);
        if($update){
          $response['status'] = 200;
          $response['error']   = false;
          $response['message'] = 'Berhasil disimpan.';
          return $response;
        }else{
          $response['status']  = 502;
          $response['error']   = true;
          $response['message'] = 'Gagal disimpan.';
          return $response;
        }
      }
    }
  }

  public function order_layanan($id_user, $layanan, $kategori, $subkategori, $alamat, $koordinat, $jam_mulai, $lama_layanan, $tipe_user, $biaya, $total_biaya, $tipe_bayar) {
    if (empty($id_user) || empty($layanan) || empty($kategori) || empty($subkategori) || empty($alamat) || empty($koordinat) || empty($jam_mulai) || empty($tipe_user) || empty($biaya) || empty($total_biaya) || empty($tipe_bayar)) {
      return $this->empty_response();
    } else {

      $timestamp = strtotime($jam_mulai) + 60*60*$lama_layanan;
      $jam_akhir = date('H:i:s', $timestamp);

      $data = array(
        'id_member'   => $id_user,
        'layanan'     => $layanan,
        'kategori'    => $kategori,
        'subkategori' => $subkategori,
        'alamat'      => $alamat,
        'koordinat'   => $koordinat,
        'tanggal'     => date('Y-m-d'),
        'jam_mulai'   => $jam_mulai.':00',
        'jam_akhir'   => $jam_akhir,
        'lama_layanan'=> $lama_layanan,
        'tipe_user'   => $tipe_user,
        'biaya'       => $biaya,
        'total_biaya' => $total_biaya,
        'status'      => 0,
        'insert_date' => date('Y-m-d H:i:s'),
        'tipe_bayar' => $tipe_bayar,
      );

      $this->db->query("SET sql_mode=''");
      $insert = $this->db->insert('order_layanan', $data);

      if($insert){
        $this->send_notif_delay($id_user, $tipe_user, 'Pesanan sedang diproses', 'Mohon tunggu beberapa saat');
        $response['status']=200;
        $response['error']=false;
        $response['message']='Pesanan telah dikirim! Mohon tunggu beberapa menit!';
        return $response;
      }else{
        $response['status']=502;
        $response['error']=true;
        $response['message']='Order gagal!';
        return $response;
      }
    }
  }

  public function data_pesanan($id_user, $tipe_user) {

    if (empty($id_user) || empty($tipe_user)) {
      return $this->empty_response();
    } else {

    	$this->db->query("SET sql_mode=''");
      $pesanan = $this->db->query("SELECT * FROM order_layanan WHERE id_member='$id_user' AND tipe_user='$tipe_user' AND status<>5 AND status<>6 ORDER BY id_order DESC");
      
      if(!empty($pesanan->result())){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['pesanan'] = $pesanan->result();
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] ='Belum ada data!';
        return $response;
      }
    }
  }

  public function data_pesanan_selesai($id_user, $tipe_user) {

    if (empty($id_user) || empty($tipe_user)) {
      return $this->empty_response();
    } else {

    	$this->db->query("SET sql_mode=''");
      $pesanan = $this->db->query("SELECT * FROM order_layanan WHERE id_member='$id_user' AND tipe_user='$tipe_user' AND (status=5 OR status=6) ORDER BY id_order DESC");
      
      if(!empty($pesanan->result())){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['pesanan'] = $pesanan->result();
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] ='Belum ada data!';
        return $response;
      }
    }
  }

  public function detail_pesanan($id_order) {

    if (empty($id_order)) {
      return $this->empty_response();
    } else {

    	$this->db->query("SET sql_mode=''");
      $pesanan = $this->db->query("SELECT * FROM order_layanan WHERE id_order='$id_order'");
      $id = $pesanan->result()[0]->id_member1;

      $petugas = '';
      
      if ($pesanan->result()[0]->tipe_petugas == 'pelamar'){
      	$this->db->query("SET sql_mode=''");
        $petugas = $this->db->query("SELECT * FROM pelamar WHERE id_pelamar='$id'");
      } else if ($pesanan->result()[0]->tipe_petugas == 'instansi'){
      	$this->db->query("SET sql_mode=''");
        $petugas = $this->db->query("SELECT * FROM instansi WHERE id_instansi='$id'");
      } else if ($pesanan->result()[0]->tipe_petugas == 'swasta'){
      	$this->db->query("SET sql_mode=''");
        $petugas = $this->db->query("SELECT * FROM swasta WHERE id_swasta='$id'");
      }

      $rating = $this->db->query("SELECT rating FROM rating WHERE id_order='$id_order'");

      // $petugas = $data->result()
      
      if(!empty($pesanan->result())){
        if (!empty($rating->result())){
          $data = $pesanan->result();
          $pesanan = (array)$data[0];
          $pesanan['rating'] = $rating->result()[0]->rating;
          // $pesanan['honor'] = $this->potongan($pesanan['total_biaya']);
          $pesanan = (object)$pesanan;
        } else {
          $data = $pesanan->result();
          $pesanan = (array)$data[0];
          $pesanan['rating'] = 0;
          // $pesanan['honor'] = $this->potongan($pesanan['total_biaya']);
          $pesanan = (object)$pesanan;
        }
      
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['pesanan'] = $pesanan;
        if($petugas != null){
          $response['petugas'] = $petugas->result()[0];
        } else {$response['petugas'] = 'empty';}
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] ='Belum ada data!';
        return $response;
      }
    }
  }

  public function cek_lokasi($provinsi, $kab_kota) {

    if (empty($provinsi) || empty($kab_kota)) {
      return $this->empty_response();
    } else {

      $this->db->query("SET sql_mode=''");
      $lokasi = $this->db->query("SELECT * FROM lokasi WHERE provinsi='$provinsi' AND kab_kota='$kab_kota'");
      $result = $lokasi->result();

      if(!empty($result)){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['lokasi']    = $result[0];
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Saufa Center Belum Tersedia di Daerah Anda!';
        return $response;
      }
    }
  }

  public function data_kategori() {

  	$this->db->query("SET sql_mode=''");
    $banner = $this->db->query("SELECT * FROM kategori WHERE status='1' ORDER BY id_kategori ASC LIMIT 3");
    $result = $banner->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['kategori']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function data_semua_kategori() {

  	$this->db->query("SET sql_mode=''");
    $banner = $this->db->query("SELECT * FROM kategori WHERE status='1' ORDER BY id_kategori ASC");
    $result = $banner->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['kategori']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function data_subkategori($kategori) {

  	$this->db->query("SET sql_mode=''");
    $banner = $this->db->query("SELECT * FROM subkategori WHERE kategori='$kategori' AND status='1' ORDER BY id_subkategori ASC");
    $result = $banner->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['subkategori']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function data_banner() {

  	$this->db->query("SET sql_mode=''");
    $banner = $this->db->query("SELECT * FROM promosi WHERE kategori='BANNER' ORDER BY id_promosi DESC");
    $result = $banner->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['banner']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function data_berita() {

  	$this->db->query("SET sql_mode=''");
    $berita = $this->db->query("SELECT * FROM promosi WHERE kategori='BERITA' ORDER BY id_promosi DESC LIMIT 5");
    $result = $berita->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['berita']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function data_bank() {
  	$this->db->query("SET sql_mode=''");
    $berita = $this->db->query("SELECT * FROM bank WHERE status='1' ORDER BY id_bank DESC");
    $result = $berita->result();

    if(!empty($result)){
      $response['status']  = 200;
      $response['error']   = false;
      $response['message'] = 'Data berhasil diambil!';
      $response['bank']    = $result;
      return $response;
    }else{
      $response['status']  = 502;
      $response['error']   = true;
      $response['message'] = 'Gagal!';
      return $response;
    }
  }

  public function isi_saldo($email, $jam, $nama_bank, $no_rek, $atas_nama, $jumlah, $keterangan, $bank_tujuan, $tipe_user, $id_user, $file_name) {

    if (empty($email) || empty($jam) || empty($nama_bank) || empty($no_rek) || empty($atas_nama) || empty($jumlah) || empty($keterangan) || empty($bank_tujuan) || empty($file_name) || empty($tipe_user) || empty($id_user)) {
      return $this->empty_response();
    } else {
      
      $data = array(
        'email'       => $email,
        'tanggal'     => date('Y-m-d'),
        'jam'         => $jam,
        'nama_bank'   => $nama_bank,
        'no_rek'      => $no_rek,
        'atas_nama'   => $atas_nama,
        'jumlah'      => $jumlah,
        'keterangan'  => $keterangan,
        'foto'        => $file_name,
        'bank_tujuan' => $bank_tujuan,
        'tipe_user'   => $tipe_user,
        'id_user'     => $id_user,
      );

      $this->db->query("SET sql_mode=''");
      $insert = $this->db->insert('isi_saldo', $data);

      if($insert){
        $response['status'] = 200;
        $response['error']   = false;
        $response['message'] = 'Berhasil Dikirim! Mohon tunggu beberapa saat.';
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Gagal dikirim.';
        return $response;
      }
    }

  }


  public function batalkan_pesanan($id_order){

    if($id_order == '' ){
      return $this->empty_response();
    }else{
      $where = array(
        "id_order"=>$id_order
      );

      $set = array(
        "status" => '6',
      );

      $this->db->query("SET sql_mode=''");
      $this->db->where($where);
      $update = $this->db->update("order_layanan",$set);
      if($update){
        $response['status']=200;
        $response['error']=false;
        $response['message']='Pesanan telah dibatalkan.';
        return $response;
      }else{
        $response['status']=502;
        $response['error']=true;
        $response['message']='Gagal. Mohon coba beberapa saat lagi.';
        return $response;
      }
    }

  }

  public function riwayat_saldo($id_user, $tipe_user, $tipe_saldo) {

    if($id_user == '' || $tipe_user == '' || $tipe_saldo == '' ){
      return $this->empty_response();
    }else{
    	$this->db->query("SET sql_mode=''");
      $saldo = $this->db->query("SELECT * FROM `saldo` WHERE tipe_saldo='$tipe_saldo' AND id_user='$id_user' AND tipe_user='$tipe_user' ORDER BY id_saldo DESC");
    //   var_dump($saldo);die;

      if(!empty($saldo->result())){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['riwayat_saldo']    = $saldo->result();
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Tidak ditemukan!';
        return $response;
      }
    }
  }

  public function riwayat_topup($id_user, $tipe_user) {

    if($id_user == '' || $tipe_user == '' ){
      return $this->empty_response();
    }else{
    	$this->db->query("SET sql_mode=''");
      $saldo = $this->db->query("SELECT * FROM `isi_saldo` WHERE id_user='$id_user' AND tipe_user='$tipe_user' ORDER BY id_isi DESC");

      if(!empty($saldo->result())){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Data berhasil diambil!';
        $response['isi_saldo']    = $saldo->result();
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Tidak ditemukan!';
        return $response;
      }
    }
  }

  public function kirim_chat($id_user_pengirim, $id_user_penerima, $tipe_pengirim, $tipe_penerima, $id_order, $chat) {
      
    $pengirim = $this->detail_user($id_user_pengirim, $tipe_pengirim);

    // var_dump($pengirim);die;
    
    $extra_data = array(
      "page"      => "Chat",
      "id_order"    => $id_order,
      "id_pengirim"   => $id_user_penerima,
      "id_penerima"   => $id_user_pengirim,
      "tipe_pengirim" => $tipe_penerima,
      "tipe_penerima" => $tipe_pengirim,
      "nama_penerima" => $pengirim->nama
    );

    $this->send_notification($id_user_penerima, $tipe_penerima, $pengirim->nama, $chat, $extra_data, 'AIzaSyAuA1nMuSdA9Ai6QAoqR4kO2vn5JVAB04Y');

    if($id_user_pengirim == '' || $id_user_penerima == '' || $id_order == '' || $chat == ''){
      return $this->empty_response();
    }else{

      $data = array(
        'id_order'            => $id_order,
        'id_user_pengirim'    => $id_user_pengirim,
        'id_user_penerima'    => $id_user_penerima,
        'chat'                => $chat,
        'insert_date'         => date('Y-m-d H:i:s'),
      );

      $this->db->query("SET sql_mode=''");
      $insert = $this->db->insert('chat', $data);

      if($insert){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Chat dikirim!';
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'gagal dikirim!';
        return $response;
      }
    }
  }

  public function data_chat($id_order) {

    if($id_order == '' ){
      return $this->empty_response();
    }else{

    	$this->db->query("SET sql_mode=''");
      $chat = $this->db->query("SELECT SQL_NO_CACHE * FROM chat WHERE id_order='$id_order' ORDER BY id_chat");

      if(!empty($chat->result())){
        $response['status']  = 200;
        $response['error']   = false;
        $response['chats'] = $chat->result();
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'gagal!';
        return $response;
      }
    }
  }

    public function akhiri_layanan($id_order, $id_petugas, $id_pemesan, $tipe_petugas, $tipe_pemesan, $rating) {

    $this->send_notification($id_petugas, $tipe_petugas, 'Pesanan Diakhiri', 'Pesanan telah selesai', '', 'AIzaSyAuA1nMuSdA9Ai6QAoqR4kO2vn5JVAB04Y');

    if (empty($id_order) || empty($id_petugas) || empty($id_pemesan) || empty($tipe_petugas) || empty($tipe_pemesan) || empty($rating)) {
      return $this->empty_response();
    } else {
      $data = array(
        'id_order'    => $id_order,
        'id_petugas'  => $id_petugas,
        'id_pemesan'  => $id_pemesan,
        'tipe_petugas'=> $tipe_petugas,
        'tipe_pemesan'=> $tipe_pemesan,
        'rating'      => $rating,
        'insert_date' => date('Y-m-d H:i:s'),

      );  

      $this->db->query("SET sql_mode=''");
      $insert = $this->db->insert('rating', $data);

      if ($insert) {
        $set = array(
          'status'    => 5,
        );

        $this->db->query("SET sql_mode=''");
        $this->db->where(array('id_order'=>$id_order));
        $update = $this->db->update('order_layanan', $set);

        if($update){

          $pesanan = $this->cek_pesanan($id_order);

          if ($pesanan->tipe_bayar == 'saldo') {
          
            $honor = $this->potongan($pesanan->total_biaya);

            $this->saldo($id_pemesan, $tipe_pemesan, 'kurang', $pesanan->total_biaya, 'membayar layanan dengan saldo');
            $this->saldo($id_petugas, $tipe_petugas, 'tambah', $honor, 'terima saldo mengajar layanan');
          } else {

            $honor = $this->potongan($pesanan->total_biaya);

            $potongan = $pesanan->total_biaya - $honor;


            $this->saldo($id_petugas, $tipe_petugas, 'kurang', $pesanan->total_biaya - $potongan, 'potong saldo dari terima cash mengajar layanan');

            $this->saldo($id_petugas, $tipe_petugas, 'kurang', $potongan, 'biaya potongan admin untuk layanan');
          }

          $this->db->query("SET sql_mode=''");
          $hapus_chat = $this->db->query("DELETE FROM chat WHERE id_order='$id_order'");

          if ($hapus_chat) {
            $response['status'] = 200;
            $response['error']   = false;
            $response['message'] = 'Berhasil! Terimakasih..';
            return $response;
          }else{
            $response['status']  = 502;
            $response['error']   = true;
            $response['message'] = 'failed to clear!.';
            return $response;
          }

          
        }else{
          $response['status']  = 502;
          $response['error']   = true;
          $response['message'] = 'Gagal dikirim.';
          return $response;
        }
      } else{
        $response['status']=502;
        $response['error']=true;
        $response['message']='Gagal!';
        return $response;
      }
    }
  }

  public function save_token($id_user, $tipe_user, $token) {

    if($id_user == '' || $tipe_user == '' || $token == ''){
      return $this->empty_response();
    }else{

      $set = array(
        'token'   => $token,
      );

      $this->db->query("SET sql_mode=''");
      $this->db->where(array('id_'.$tipe_user=>$id_user));
      $update = $this->db->update($tipe_user, $set);

      if($update){
        $response['status']  = 200;
        $response['error']   = false;
        $response['message'] = 'Token saved!';
        return $response;
      }else{
        $response['status']  = 502;
        $response['error']   = true;
        $response['message'] = 'Failed to save token!';
        return $response;
      }
    }
  }

  public function send_notif_delay($id_user, $tipe_user, $title, $subtitle) {
    // sleep($delay);
    $this->send_notification($id_user, $tipe_user, $title, $subtitle, '', 'AIzaSyBN_ybuTjw-4KIaXU5dzO0oIupuc6x5uB8');
  }

  public function send_notification($id_user, $tipe_user, $title, $body, $data="", $key) {

    $arrNotificationMessage = array(
                              'title'   => $title,
                              'body'    => $body,
                              'sound'   => "default",
                              'priority'=> "high",
                              'android_channel_id' => "test-channel",
                );

    $extraData = array(
              'extra_data'    => $data
            );

    $deviceToken    =   $this->get_token_user($id_user, $tipe_user);
    // "clSmYFbfFEc:APA91bHgUo9XEFmvsm4rKsQRDGrYkyuJv8e1CYsg8fw1IIicUTQp4w4SICVS82eI0Qp-3ji_OVP5JZ6W_1e6tehpmBQnc6UE0pfJIO_ltg2xRnR7rVbuQqGBUQuS0Buj6K5UCD8cD6We";

    //cop-LV2AyDY:APA91bFb3S77PX8A-OdGbp-bMyQk7kLm1gRz79d0TI6LCewfqKKzAewPvdErr12RIRmo3-24-VtPfrABXoZ5_c3HNhKwn5h3RWvWymyyB6gaXNPvbhJkYyDxTMCOzzSleyJby8LR5o8G

    $APIkey = $key;

    $topics = "/topics/KotakMasukAll";

    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
    
      $header = array(
              'Content-Type: application/json',
                "Authorization: key=".$APIkey
              );

      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

      curl_setopt($ch, CURLOPT_POST, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": ".json_encode($arrNotificationMessage).", \"data\":" . json_encode($extraData) . ", \"to\" : ".json_encode($deviceToken)."}");

      $response =   curl_exec($ch);
      curl_close($ch);

      // echo json_encode($result);
      return $response;

    }
    
    public function send_notif_all($title, $body) {

    $arrNotificationMessage = array(
                              'title'   => $title,
                              'body'    => $body,
                              'sound'   => "default",
                              'priority'=> "high",
                               'android_channel_id' => "test-channel"
                );

    $extraData = array(
              'any_extra_data'    =>"any data"
            );

    // $deviceToken    =   $this->get_token_user($id_user, $tipe_user);
    // "clSmYFbfFEc:APA91bHgUo9XEFmvsm4rKsQRDGrYkyuJv8e1CYsg8fw1IIicUTQp4w4SICVS82eI0Qp-3ji_OVP5JZ6W_1e6tehpmBQnc6UE0pfJIO_ltg2xRnR7rVbuQqGBUQuS0Buj6K5UCD8cD6We";

    //cop-LV2AyDY:APA91bFb3S77PX8A-OdGbp-bMyQk7kLm1gRz79d0TI6LCewfqKKzAewPvdErr12RIRmo3-24-VtPfrABXoZ5_c3HNhKwn5h3RWvWymyyB6gaXNPvbhJkYyDxTMCOzzSleyJby8LR5o8G

    $APIkey = 'AIzaSyBN_ybuTjw-4KIaXU5dzO0oIupuc6x5uB8';

    $topics = "/topics/saufa";

    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
    
      $header = array(
              'Content-Type: application/json',
                "Authorization: key=".$APIkey
              );

      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

      curl_setopt($ch, CURLOPT_POST, 0);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": ".json_encode($arrNotificationMessage).", \"data\":" . json_encode($extraData) . ", \"to\" : ".json_encode($topics)."}");

      $response =   curl_exec($ch);
      curl_close($ch);

      // echo json_encode($result);
      return $response;

    }

  /* ----- INTERNAL FUNCTION ------> */

  private function total_saldo($id_user, $tipe_user){
  	$this->db->query("SET sql_mode=''");
    $penambahan_saldo = $this->db->query("SELECT SUM(`jumlah`) as saldo FROM `saldo` WHERE tipe_saldo='tambah' AND id_user='$id_user' AND tipe_user='$tipe_user'");
    $this->db->query("SET sql_mode=''");
    $pemakaian_saldo = $this->db->query("SELECT SUM(`jumlah`) as saldo FROM `saldo` WHERE tipe_saldo='kurang' AND id_user='$id_user' AND tipe_user='$tipe_user'");

    $total_saldo = $penambahan_saldo->result()[0]->saldo - $pemakaian_saldo->result()[0]->saldo;

    if($penambahan_saldo AND $pemakaian_saldo){
        return $total_saldo;
      }else{
        return null;
      }
  }
  
  public function potongan($total_biaya) {
    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT potongan FROM `bagi_hasil` WHERE id_bagi='1'");
    $potongan = $q->result()[0]->potongan;

    $persen = $potongan / 100;
    $potong = $total_biaya * $persen;
    return $total_biaya - $potong;
  }

  private function saldo($id_user, $tipe_user, $tipe_saldo, $jumlah, $keterangan) {

    $data = array(
            'id_user'     => $id_user,
            'tipe_user'   => $tipe_user,
            'tipe_saldo'  => $tipe_saldo,
            'jumlah'      => $jumlah,
            'keterangan'  => $keterangan,
            'insert_date' => date('Y-m-d'),
          );

    $this->db->query("SET sql_mode=''");
    $insert = $this->db->insert('saldo', $data);
    if ($insert) {
      return true;
    } else {
      return false;
    }

  }

  private function cek_pesanan($id_order) {

  	$this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT * FROM order_layanan WHERE id_order='$id_order'");
    return $q->result()[0];

  }

  private function get_token_user($id_user, $tipe_user) {

    $id = "id_".$tipe_user;

    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT token FROM $tipe_user WHERE $id='$id_user'");
    if ($q) {
      return $q->result()[0]->token;
    } else {
      return null;
    }

  }
  
  private function detail_user($id_user, $tipe_user) {

    $id = 'id_'.$tipe_user;

    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT * FROM $tipe_user WHERE $id='$id_user'");
    if ($q) {
      return $q->result()[0];
    } else {
      return null;
    }

  }

  private function is_username_exist($username) {

    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT * FROM admin WHERE username='$username'");

    if(count($q->result())>0){
      return true;
    }else{
      return false;            
    }
  }

  private function is_uid_exist($uid) {

    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT * FROM admin WHERE uid='$uid'");

    if(count($q->result())>0){
      return true;
    }else{
      return false;            
    }
  }

  private function get_last_kode_transaksi() {
    $this->db->query("SET sql_mode=''");
    $q = $this->db->query("SELECT * FROM transaksi ORDER BY kode_transaksi DESC LIMIT 1");

    if(count($q->result())>0){
      return true;
    }else{
      return "0";            
    }
  }

}

?>
