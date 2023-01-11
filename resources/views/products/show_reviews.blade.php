<div>
    {!! $arrReviewListing->links() !!}
</div>

@foreach ($arrReviewListing as $review)
    <div class="user-review-item mb-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center user-review-item-meta pb-3">
                    <img id="fileManagerPreview" src="{{ $review->user->uploads->getImageOptimizedFullName(100,100) }}" class="reviewer_avatar border rounded-circle h-60px mr-15px">
                    <div class="review-details-meta">
                        <div class="fs-20 fw-600 reviewer_name w-100">{{ $review->user->first_name }} {{ $review->user->last_name }}</div>
                        <div class="row">
                            <div class="col-auto pr-0">
                                <div class="flex">
                                    @for($i = 0; $i < $review->rating; $i ++)
                                        <img src="/assets/img/star_blue.png" width="25"/>
                                    @endfor
                                    @for($i = 0; $i < 5 - $review->rating; $i ++)
                                        <img src="/assets/img/star_gray.png" width="25"/>
                                    @endfor
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="rated_date fs-16 opacity-70">Rated at {{ date_format($review->updated_at, "F j, Y") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="py-3">
                    {{ $review->review }}
                </div>
            </div>
        </div>
    </div>
@endforeach

<div>
    {!! $arrReviewListing->links() !!}
</div>

<script>
$('.star-ratings').each(function() {
    var star_rating_width = $('.fill-ratings span', this).width();
    $(this).width(star_rating_width);
});
</script>
