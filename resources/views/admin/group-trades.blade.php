@extends('layouts.admin')

@section('title', 'Group Trades')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Group Trades</h4>
                    <p class="card-category">Manage and view grouped trades</p>
                </div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-id">
                                    <label class="control-label" for="tradessearch-id">ID</label>
                                    <input type="text" id="tradessearch-id" class="form-control" name="id">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-commodity">
                                    <label class="control-label" for="tradessearch-commodity">Scrip</label>
                                    <input type="text" id="tradessearch-commodity" class="form-control" name="scrip_id">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-segment">
                                    <label class="control-label" for="tradessearch-segment">Segment</label>
                                    <select name="segment" class="form-control">
                                        <option value="All">All</option>
                                        <option value="NSE">Equity</option>
                                        <option value="MCX">MCX</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-user_id">
                                    <label class="control-label" for="tradessearch-user_id">User ID</label>
                                    <input type="text" id="tradessearch-user_id" class="form-control" name="userid">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-buy_rate">
                                    <label class="control-label" for="tradessearch-buy_rate">Buy Rate</label>
                                    <input type="text" id="tradessearch-buy_rate" class="form-control" name="buy_rate">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-sell_rate">
                                    <label class="control-label" for="tradessearch-sell_rate">Sell Rate</label>
                                    <input type="text" id="tradessearch-sell_rate" class="form-control" name="sell_rate">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <div class="form-group field-tradessearch-lots">
                                    <label class="control-label" for="tradessearch-lots">Lots / Units</label>
                                    <input type="text" id="tradessearch-lots" class="form-control" name="lots">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Search</button>
                            <button type="reset" class="btn btn-default" onclick="document.getElementById('trade_search').reset();">Reset</button>
                        </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="text-primary">
                                <tr>
                                    <th>Actions</th>
                                    <th>ID</th>
                                    <th>Scrip</th>
                                    <th>Segment</th>
                                    <th>User ID</th>
                                    <th>Buy Rate</th>
                                    <th>Sell Rate</th>
                                    <th>Lots / Units</th>
                                    <th>Bought at</th>
                                    <th>Sold at</th>
                                </tr>
                            </thead>
                            <tbody>
                              <tr data-key="3780209">
                                        <td class="text-nowrap">
                                            <a href="trades-view.aspx?id=3780209" style="color: black;" title="View" aria-label="View" data-pjax="0">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path>
                                                </svg>
                                            </a>
                                            <a href="Update-Trades.aspx?id=3780209" style="color: black;" title="Update" aria-label="Update" data-pjax="0">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path>
                                                </svg>
                                            </a>
                                            <a href="delete-trade.aspx?id=3780209" style="color: black;" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: .875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path>
                                                </svg>
                                            </a>
                                        </td>
                                        <td class="text-nowrap">3780209</td>
                                        <td class="text-nowrap">GOLD25FEBFUT</td>
                                        <td class="text-nowrap">MCX</td>
                                        <td class="text-nowrap">6355 Hs01 : Manish </td>
                                        <td class="text-nowrap">80013</td>
                                        <td class="text-nowrap">80035</td>
                                        <td class="text-nowrap">0.1 / 10</td>
                                        <td class="text-nowrap">2025-01-27 18:32:57</td>
                                        <td class="text-nowrap"><span class="not-set">2025-01-27 18:21:03</span></td>
                                    </tr>
                                    <tr data-key="3780208">
                                        <td class="text-nowrap">
                                            <a href="trades-view.aspx?id=3780208" style="color: black;" title="View" aria-label="View" data-pjax="0">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path>
                                                </svg>
                                            </a>
                                            <a href="Update-Trades.aspx?id=3780208" style="color: black;" title="Update" aria-label="Update" data-pjax="0">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: 1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path>
                                                </svg>
                                            </a>
                                            <a href="delete-trade.aspx?id=3780208"  style="color: black;" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post">
                                                <svg aria-hidden="true" style="display: inline-block; font-size: inherit; height: 1em; overflow: visible; vertical-align: -.125em; width: .875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path>
                                                </svg>
                                            </a>
                                        </td>
                                        <td class="text-nowrap">3780208</td>
                                        <td class="text-nowrap">GOLD25FEBFUT</td>
                                        <td class="text-nowrap">MCX</td>
                                        <td class="text-nowrap">6355 Hs01 : Manish </td>
                                        <td class="text-nowrap">80013</td>
                                        <td class="text-nowrap">80037</td>
                                        <td class="text-nowrap">0.8 / 80</td>
                                        <td class="text-nowrap">2025-01-27 18:32:57</td>
                                        <td class="text-nowrap"><span class="not-set">2025-01-27 18:20:44</span></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('.table').DataTable();
    });
</script>
@endsection
