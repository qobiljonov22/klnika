<?php
$current = klinika_current_lang();
$ru = add_query_arg('lang', 'ru');
$en = add_query_arg('lang', 'en');
?>
<div class="flex items-center gap-1 font-[Montserrat] text-[13px] sm:text-[14px] font-semibold">
    <a href="<?php echo esc_url($ru); ?>" class="no-underline <?php echo $current === 'ru' ? 'text-[#009BE3]' : 'text-[#9A9A9A] hover:text-[#009BE3]'; ?>">RU</a>
    <span class="text-[#D0D0D0]">|</span>
    <a href="<?php echo esc_url($en); ?>" class="no-underline <?php echo $current === 'en' ? 'text-[#009BE3]' : 'text-[#9A9A9A] hover:text-[#009BE3]'; ?>">EN</a>
</div>
