#!/bin/bash

if [ ! -f .env ]; then
  echo "找不到 .env 檔案。請參考 .env.example 設定 Bearer Token 。"
  exit 1
fi

# 讀取 .env
export "$(grep -v '^#' .env | xargs)"

API_PREFIX="https://tdx.transportdata.tw/api/basic/v2/Rail/Metro/"

declare -A api_endpoints
api_endpoints=(
  ["line-KRTC"]="${API_PREFIX}Line/KRTC?\$top=100&\$format=JSON"
  ["station-KRTC"]="${API_PREFIX}Station/KRTC?\$top=100&\$format=JSON"

  ["line-KLRT"]="${API_PREFIX}Line/KLRT?\$top=100&\$format=JSON"
  ["station-KLRT"]="${API_PREFIX}Station/KLRT?\$top=100&\$format=JSON"
)

fetch_data() {
  local endpoint_key=$1
  local output_file=$2

  curl -X 'GET' \
    "${api_endpoints[$endpoint_key]}" \
    -H 'accept: application/json' \
    -H "Authorization: Bearer $BEARER_TOKEN" \
    -o "ref/$endpoint_key.json"
}

fetch_data "line-KRTC"
fetch_data "station-KRTC"
fetch_data "line-KLRT"
fetch_data "station-KLRT"
