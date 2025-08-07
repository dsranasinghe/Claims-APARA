<div class="row g-4 mb-4">
    @foreach($cards as $card)
    <div class="col-md-3 col-sm-6">
        <x-cards.summary-card 
            :icon="$card['icon']" 
            :label="$card['label']" 
            :count="$card['count']" 
            :color="$card['color']" 
        />
    </div>
    @endforeach
</div>