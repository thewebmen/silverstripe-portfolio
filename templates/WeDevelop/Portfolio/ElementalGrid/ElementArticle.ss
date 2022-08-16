<% if $ShowTitle %>
    <$TitleTag class="$TitleSizeClass">$Title.RAW</$TitleTag>
<% end_if %>
<hr />
<% if $ArticlePage %>
    <% with $ArticlePage %>
        <h4>$Title</h4>
        Reading Time: $Author.ReadingTime<br />
        Author: $Author.Name<br />
    <% end_with %>
<% else %>
    <p><%t TheWebmen\Articles\ElementalGrid\ElementArticle.NOARTICLEFOUND "No article found" %></p>
<% end_if %>
<% if $ShowMoreArticlesButton %>
    <a href="$ArticlesPage.Link" class="btn button is-primary btn-sm btn-primary">
        $ShowMoreArticlesButtonText
    </a>
<% end_if %>
