<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
<hr />
$Articles.Count <%t Article.Plural "Articles" %>
<hr /><br />
<h3><%t ElementArticles.Results "Results" %></h3>
<% if $Articles %>
    <ul>
        <% loop $Articles %>
            <li>
                <% if $Pinned %>Pinned<% end_if %>
                <% if $Highlighted %>Highlighted<% end_if %>
                <a href="$Link">$Title</a> <% if $PublicationDate %>($PublicationDate.Nice)<% end_if %>
            </li>
        <% end_loop %>
    </ul>
<% else %>
    <p><%t TheWebmen\Articles\ElementalGrid\ElementArticles.NOARTICLESFOUND "No articles found" %></p>
<% end_if %>
<% if $ShowMoreArticlesButton %>
    <a href="$ArticlesPage.Link" class="btn button is-primary is-small btn-sm btn-primary">
        $ShowMoreArticlesButtonText
    </a>
<% end_if %>
