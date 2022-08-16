<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
<hr />
$Cases.Count <%t Case.Plural "Cases" %>
<hr /><br />
<h3><%t ElementPortfolio.Results "Results" %></h3>
<% if $Cases %>
    <ul>
        <% loop $Cases %>
            <li>
                <a href="$Link">$Title</a> <% if $PublicationDate %>($PublicationDate.Nice)<% end_if %>
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t WeDevelop\Portfolio\ElementalGrid\ElementPortfolio.NOCASESFOUND "No cases found" %></p>
<% end_if %>
<% if $ShowMoreCasesButton %>
    <a href="$PortfolioPage.Link" class="btn button is-primary btn-sm btn-primary">
        $ShowMoreCasesButtonText
    </a>
<% end_if %>
