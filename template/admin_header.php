<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('/style.css');?>">
    <style>
        .table {width: 100%; border-collapse: collapse;}
        .table th, .table td {border: 1px solid #ddd; padding: 8px;}
        .table th {background-color: #1A6B3A; color: white;}
        .btn {background-color: #1A6B3A; color: white; padding: 5px 10px; 
              text-decoration: none; border-radius: 3px;}
        .btn-danger {background-color: #dc3545;}
    </style>
</head>
<body>
    <div id="container">
        <header>
            <h1>Admin Portal Berita</h1>
        </header>
        <nav>
            <a href="<?= base_url('/admin/artikel');?>">Dashboard</a>
            <a href="<?= base_url('/admin/artikel');?>">Artikel</a>
            <a href="<?= base_url('/admin/artikel/add');?>">Tambah Artikel</a>
        </nav>
        <section id="wrapper">
            <section id="main">