@extends('layouts.mail')

@section('title', 'Inbox')

@section('content')
    <div class="row">
        <div class="col-sm-2">
            @include('anthaleja.messages.partials.sidebar')
        </div>

        <div class="col-sm-10">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Inbox (7)</h3>
                    <form action="#" class="d-flex">
                        <input type="text" class="form-control me-2" placeholder="Search mail">
                        <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr class="table-active">
                                    <td>
                                        <input type="checkbox" class="form-check-input">
                                    </td>
                                    <td>
                                        <a href="#" class="text-warning"><i class="bi bi-star-fill"></i></a>
                                    </td>
                                    <td>
                                        <div class="media">
                                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar"
                                                class="img-fluid rounded-circle me-2" width="40">
                                            <div class="media-body">
                                                <h6 class="mb-0">John Kribo</h6>
                                                <p class="text-muted mb-0">Commits pushed - Lorem ipsum dolor sit amet...
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">Today at 6:16 AM</td>
                                </tr>
                                <!-- More rows like above -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <div class="btn-group">
                                <button class="btn btn-outline-secondary btn-sm">More</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#" class="dropdown-item">Mark as read</a></li>
                                    <li><a href="#" class="dropdown-item">Spam</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a href="#" class="dropdown-item">Delete</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Showing 1-50 of 2,051 messages</span>
                        <div class="btn-group">
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i
                                    class="bi bi-chevron-left"></i></a>
                            <a href="#" class="btn btn-outline-secondary btn-sm"><i
                                    class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
