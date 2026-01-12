<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            "vless://ebf183d2-a65e-40bc-b835-b3591af192f4@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-lki5h18f",
            "vless://257490a0-8192-451b-aff4-db34c368cf16@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-y0dkzjb1",
            "vless://f4691185-7bf2-4d57-83e5-907f326792f5@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-1352dsp8",
            "vless://024f007c-ea2f-4f3c-b469-ea514dc2f1aa@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-jhc3tx0m",
            "vless://d95eb87e-ce24-42ed-8b7e-75de26187b77@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-a9csqnvj",
            "vless://cdffb9e1-1e2e-4e17-a2a3-43438ba29ec9@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-yeeem14j",
            "vless://47cf096f-6a78-4102-9de3-c2d1075737a5@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-gy07vct2",
            "vless://c04eb278-2a45-4511-aeda-4cd58befcb35@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-u0cs5nak",
            "vless://7a8d7592-770c-4e40-8257-b43573776b0a@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-wjj7sixd",
            "vless://dc97a7bb-c454-448d-b612-3a5faf6e800b@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-u96eqxbw",
            "vless://3e4676dc-6536-474c-9b5e-a7ef5cc63f8d@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-mdujdl8r",
            "vless://cd980c97-70ec-4f6c-8bc2-930ed03976c0@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-tuqxgl9w",
            "vless://d20ead91-8ab6-44ea-a419-8e888c0c355c@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-q3ipnvb7",
            "vless://d92c51a4-3567-4b7b-bcf4-9be8b325e8fe@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-midbr4gc",
            "vless://6d95d44d-240d-44a9-9514-9636e3a567f2@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-lyasz0og",
            "vless://cd3ff9b8-10f9-49e9-a8b3-b4983695aca0@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-uqat8m3b",
            "vless://a3d916f5-576c-4787-9726-f9675f44dad6@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-uilvppwy",
            "vless://d086efcf-e0ff-4e42-b576-54797ab16b3a@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-l0v0zspt",
            "vless://55fbc226-bb39-4e0c-8a63-a79037dbef44@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-xwgidgv9",
            "vless://6c4e5c88-e37a-4cef-bac0-42519d4e37a8@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-h4sif7sz",
            "vless://fdd2685b-e029-4b35-ba46-925783e075bc@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-gi75vfud",
            "vless://da7879f0-b573-4f3c-a90f-98a70bb55b7b@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-y6uhu1zl",
            "vless://e50e32dc-e278-4b5b-8011-51770e4ea3aa@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-j8362qem",
            "vless://06290f54-07f2-4eac-8005-fe12653fc779@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-or1008it",
            "vless://4a0f2494-11d0-4862-b0fe-8acdf539f852@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-8r7yx52q",
            "vless://1d3fc40b-9907-492b-8e3b-1040793450ad@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-ld5j28sq",
            "vless://29ec1281-ad7e-4a05-acdd-b19d552b38fa@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-zhcq3di5",
            "vless://68e3098f-1bb6-45c4-a403-d286c95ff501@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-r7e25sgh",
            "vless://5ed470ef-b0b7-4d73-b4e1-7a2379fce7da@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-2yjtt66q",
            "vless://ea903cb9-1480-410a-98ba-1944ae9c370b@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-omg6khg0",
            "vless://f5447091-4a7a-4781-ade8-189c0390385d@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-ng2xjp14",
            "vless://393ecd07-5f8d-4a5b-bcb9-505d959e627e@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-r74wbqlz",
            "vless://dc5255a9-6d34-4ad3-9ae2-d57d654ae627@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-o2hjwu48",
            "vless://46d3c799-58ba-4e21-a5bf-3c2066377da3@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-47j08zms",
            "vless://1068c4aa-3a1a-44cf-816b-31666f794b41@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-vuc19ctg",
            "vless://f5cfd624-738a-4bc4-988c-89d17de3c5cd@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-s4d4cfds",
            "vless://acee05b4-f25f-4f2c-a9a3-45201fcfabb8@2026.denpiligrim.ru:443?type=tcp&encryption=none&security=reality&pbk=XM--vqdtlCRPBXETmG2K1csoKBWqAMRsF_DmL-3zo3k&fp=chrome&sni=ads.x5.ru&sid=3d&spx=%2F&flow=xtls-rprx-vision#%F0%9F%87%B0%F0%9F%87%BF%20vless-tcp-reality-bs8aoze4",
        ];

        foreach ($links as $link) {
            \App\Models\Reward::create([
                'link'   => $link,
                'status' => 0,
            ]);
        }
    }
}
