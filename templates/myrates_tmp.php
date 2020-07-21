    <nav class="nav">
    <?= $menu_lot; ?>
    </nav>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">

      <?php foreach ($my_rates as $key => $value): ?>     
        <tr class="rates__item">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?= $value['photo']; ?>" width="54" height="40" alt="Сноуборд">
            </div>
            <h3 class="rates__title"><a href="lot.php?id=<?= $value['id']; ?>"><?= $value['name']; ?></a></h3>
          </td>
          <td class="rates__category">
          <?= $value['category']; ?>
          </td>
          <td class="rates__timer">
          <?= $value['date_create']; ?>
                </div>
          </td>
          <td class="rates__price">
            <?= $value['cost']; ?>
          </td>
          
        </tr>       
        <?php endforeach; ?>
              
      </table>
    </section>
  