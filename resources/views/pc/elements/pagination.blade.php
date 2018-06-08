<div class="col-sm-5">
    <div class="dataTables_info" id="datatable_info" role="status" aria-live="polite"> {{ $pages->firstItem() }} {{ $pages->lastItem()  }}  {{ $pages->total()  }} </div>
</div>
<div class="col-sm-7 text-right">
    {{ $pages->appends($_GET)->links() }}
</div>