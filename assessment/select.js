// 大分類、小分類の選択肢を配列でそれぞれ用意
const categories = [
  '外国語学部',
  '国際教養学部',
  '経済学部',
  '法学部'
];

// 小分類は、大分類と紐付けるためにオブジェクト型を使う
const subCategories = [
  {category: '外国語学部', name: 'ドイツ語学科'},
  {category: '外国語学部', name: '英語学科'},
  {category: '外国語学部', name: 'フランス学科'},
  {category: '外国語学部', name: '交流文化学科'},
  {category: '国際教養学部', name: '言語文化学科'},
  {category: '経済学部', name: '経済学科'},
  {category: '経済学部', name: '経営学科'},
  {category: '経済学部', name: '国際環境経済学科'},
  {category: '法学部', name: '法律学科'},
  {category: '法学部', name: '国際関係法学科'},
  {category: '法学部', name: '総合政策学科'}
];

const categorySelect1 = document.getElementById('category-select-1');
const subCategorySelect1 = document.getElementById('sub-category-select-1');

// 大分類のプルダウンを生成
categories.forEach(category => {
  const option = document.createElement('option');
  option.textContent = category;
  option.value = category;

  categorySelect1.appendChild(option);    
});

// 大分類が選択されたら小分類のプルダウンを生成
categorySelect1.addEventListener('input', () => {

  // 小分類のプルダウンをリセット
  const options = document.querySelectorAll('#sub-category-select-1 > option');
  options.forEach(option => {
    option.remove();
  });

  // 小分類のプルダウンに「選択してください」を加える
  const firstSelect = document.createElement('option');
  firstSelect.textContent = '選択してください';
  subCategorySelect1.appendChild(firstSelect);

  // 大分類で選択されたカテゴリーと同じ小分類のみを、プルダウンの選択肢に設定する
  subCategories.forEach(subCategory => {
    if (categorySelect1.value == subCategory.category) {
      const option = document.createElement('option');
      option.textContent = subCategory.name;
      option.value = subCategory.name;
      
      subCategorySelect1.appendChild(option);
    }
  });
});