# 高雄捷運及輕軌站地址及經緯度相關資料

資料來源： [TDX](https://tdx.transportdata.tw/)

## 用法

* 因應 TDX 收費，需[新增 API Key](https://tdx.transportdata.tw/user/dataservice/key) ，取得 Client ID 及 Client Secret 填入 Swagger API 以取得 Bearer Token 。
* 將取得的 Bearer Token 放置於 `.env` 檔案中，並利用 `src/fetch_data.bash` 取得最新的 API 回傳資料。

## LICENSE

根據[政府資料開放授權條款－第 1 版](https://data.gov.tw/license)，使用[姓名標示 4.0 國際 (CC BY 4.0)](https://creativecommons.org/licenses/by/4.0/deed.zh_TW)
