{{--
    Renders Bootstrap 5 pagination links only when there is more than one page.

    Props:
        paginator – a LengthAwarePaginator instance
--}}
@props(['paginator'])

@if($paginator->hasPages())
    <div class="d-flex justify-content-center py-3 px-4">
        {{ $paginator->links() }}
    </div>
@endif
