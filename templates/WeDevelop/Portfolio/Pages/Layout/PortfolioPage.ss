<section class="block section">
    <div class="container">
        <h1>$Title</h1>
        <% if $UseElementalGrid %>
            $ElementalArea
        <% else_if $Content %>
            $Content
        <% end_if %>
        <hr />
        <div class="columns row">
            <div class="col-md-4 column is-4-desktop">
                <div class="card card-body">
                    $CasesFilterForm
                </div>
            </div>
            <div class="col-md-8 column is-8-desktop">
                <h2><%t TheWebmen\Articles\Pages\CasePage.PLURALNAME "Cases" %></h2>
                <hr />
                <% if $Categories %>
                    <h3>
                        <%t Category.Plural "Categories" %>
                    </h3>
                    <ul>
                        <% loop $Categories %>
                            <li>
                                <span>$Title</span>
                            </li>
                        <% end_loop %>
                    </ul>
                    <hr />
                <% end_if %>

                <h3>All cases (paginated)</h3>
                <% if $PaginatedCases %>
                    <ul>
                        <% loop $PaginatedCases %>
                            <li>
                                <a href="$Link">$Title</a> <% if $PublicationDate %>($PublicationDate.Nice)<% end_if %>
                            </li>
                        <% end_loop %>
                    </ul>
                    <% with $PaginatedCases %>
                        <% if $MoreThanOnePage %>
                            <% if $NotFirstPage %>
                                <a class="prev" href="$PrevLink"><<</a>
                            <% end_if %>
                            <% loop $Pages %>
                                <% if $CurrentBool %>
                                    $PageNum
                                <% else %>
                                    <% if $Link %>
                                        <a href="$Link">$PageNum</a>
                                    <% else %>
                                        ...
                                    <% end_if %>
                                <% end_if %>
                            <% end_loop %>
                            <% if $NotLastPage %>
                                <a class="next" href="$NextLink">>></a>
                            <% end_if %>
                        <% end_if %>
                    <% end_with %>
                <% else %>
                    <p>
                        <%t Articles.NoArticlesFound "No cases found" %>
                    </p>
                <% end_if %>
            </div>
        </div>
    </div>
</section>
