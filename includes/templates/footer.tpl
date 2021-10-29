    <footer>
      Unknown Technology Solutions 2017-{$currentYear}<br />
      {foreach from=$footerMenu key=label item=url}
      <a href="{$url}">{$label}</a>
      {/foreach}
    </footer>
  </div>
</div>
</body>
</html>