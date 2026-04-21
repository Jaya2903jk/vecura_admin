<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Category Master <span
                            class="badge badge-soft-primary border border-primary ms-2">Total Categories : 6</span></h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Category</a>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Linked Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Support</td>
                                    <td>General support and operational concerns</td>
                                    <td>Front Office</td>
                                    <td><span class="badge badge-soft-success border border-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Finance</td>
                                    <td>Billing, receipts and payments</td>
                                    <td>Billing</td>
                                    <td><span class="badge badge-soft-success border border-success">Active</span></td>
                                </tr>
                                <tr>
                                    <td>Technical</td>
                                    <td>Software and access related issues</td>
                                    <td>IT</td>
                                    <td><span class="badge badge-soft-info border border-info">Active</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="add_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Add New Category</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form action="category-master-fixed.html">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" class="form-control" placeholder="Enter category name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="3" placeholder="Enter description"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Linked Department</label>
                                    <select class="form-control">
                                        <option>Front Office</option>
                                        <option>Billing</option>
                                        <option>IT</option>
                                        <option>Nursing</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control">
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add New Category</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">2026 &copy; <a href="javascript:void(0);" class="link-primary">Preclinic</a>, All
                Rights Reserved</p>
        </div>
    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
