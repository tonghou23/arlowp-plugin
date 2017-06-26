<div class="arlo arlo-boxed" id="arlo">
    [arlo_all_oa_filters]

    <ul class="arlo-online-activities arlo-list">
        [arlo_all_oa_list_item limit="10" group="category"]
        [arlo_group_divider wrap='<li class="arlo-cf arlo-online-activity arlo-group-divider">%s</li>']
        <li class="arlo-cf arlo-online-activity">
            <h4>[arlo_event_template_permalink wrap='<a href="%s">'][arlo_oa_name]</a></h4>

            [arlo_oa_reference_term]

            [arlo_oa_credits]
                
            [arlo_oa_delivery_description wrap='<div class="arlo-delivery-desc">%s</div>']

            [arlo_event_template_summary wrap='<p class="arlo-summary">%s</p>']

            [arlo_oa_offers]

            [arlo_oa_registration]
        </li>
        [/arlo_all_oa_list_item]
    </ul>

    [arlo_all_oa_list_pagination limit="10" wrap='<div class="arlo-pagination">%s</div>']

</div>