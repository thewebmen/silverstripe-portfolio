<section class="block section">
    <div class="container">
        <h1>$Title</h1>
        $Thumbnail.Fill(200,200)
        <h2>$Subtitle</h2>
        <ul>
            <% if $PublicationDate %>
                <li>$PublicationDate.Nice</li>
            <% end_if %>
        </ul>
        <% if $Customer %>
            <span>$Customer.Title</span><br/>
        <% end_if %>
        <hr />
        $Content
        <hr />
        <h3><%t Category.Plural "Categories" %></h3>
        <ul>
            <% loop $Categories %>
                <li>
                    <span>$Title</span>
                </li>
            <% end_loop %>
        </ul>
    </div>
</section>
